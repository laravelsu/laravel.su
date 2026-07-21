import { Controller } from '@hotwired/stimulus';
import { copyText } from '../helpers/clipboard.js';

export default class extends Controller {
    static targets = ['currentSection', 'searchTrigger'];

    /**
     * Port for laravel.com
     */
    connect() {
        this.highlightSupportPolicyTable();
        this.setupCodeCopyButtons();
        this.setupTableOfContents();
    }

    disconnect() {
        if (this.updateActiveSection) {
            window.removeEventListener('scroll', this.updateActiveSection);
            window.removeEventListener('resize', this.updateActiveSection);
        }
    }

    openSearch(event) {
        if (event.key.toLowerCase() !== 'k' || (!event.metaKey && !event.ctrlKey)) {
            return;
        }

        event.preventDefault();

        if (this.hasSearchTriggerTarget) {
            this.searchTriggerTarget.click();
        }
    }

    toggleMenuSection(event) {
        const button = event.currentTarget;
        const willExpand = button.getAttribute('aria-expanded') !== 'true';

        if (willExpand) {
            this.element.querySelectorAll('.docs-menu-section[aria-expanded="true"]').forEach((openButton) => {
                if (openButton !== button) {
                    this.setMenuSectionState(openButton, false);
                }
            });
        }

        this.setMenuSectionState(button, willExpand);
    }

    setMenuSectionState(button, expanded) {
        const panel = document.getElementById(button.getAttribute('aria-controls'));

        if (!panel) {
            return;
        }

        button.setAttribute('aria-expanded', String(expanded));
        button.classList.toggle('collapsed', !expanded);
        panel.dataset.expanded = String(expanded);
        panel.setAttribute('aria-hidden', String(!expanded));
        panel.inert = !expanded;
    }

    setupCodeCopyButtons() {
        this.element.querySelectorAll('.documentations pre').forEach((pre) => {
            if (pre.closest('.docs-code-block')) {
                return;
            }

            const code = pre.querySelector('code');

            if (!code) {
                return;
            }

            let container = pre.parentElement;

            if (!container.classList.contains('code-container')) {
                container = document.createElement('div');
                pre.before(container);
                container.append(pre);
            }

            container.classList.add('docs-code-block');

            const button = document.createElement('button');
            button.type = 'button';
            button.className = 'docs-code-copy';
            button.innerHTML = '<span class="docs-code-copy-icon" aria-hidden="true"></span>';
            button.setAttribute('aria-label', 'Скопировать код');
            button.title = 'Скопировать код';

            const toolbar = document.createElement('div');
            toolbar.className = 'docs-code-toolbar';

            const toolbarLabel = document.createElement('span');
            toolbarLabel.textContent = 'Фрагмент кода';
            toolbar.append(toolbarLabel, button);

            button.addEventListener('click', async () => {
                const copied = await copyText(code.textContent);

                if (!copied) {
                    button.setAttribute('aria-label', 'Не удалось скопировать код');
                    button.title = 'Не удалось скопировать код';
                    return;
                }

                button.dataset.copied = 'true';
                button.setAttribute('aria-label', 'Код скопирован');
                button.title = 'Код скопирован';

                window.setTimeout(() => {
                    button.dataset.copied = 'false';
                    button.setAttribute('aria-label', 'Скопировать код');
                    button.title = 'Скопировать код';
                }, 1600);
            });

            container.prepend(toolbar);
        });
    }

    setupTableOfContents() {
        this.sectionHeadings = Array.from(this.element.querySelectorAll('.documentations h2, .documentations h3'))
            .filter((heading) => heading.querySelector('a[name], a[id], a[href^="#"]'));

        if (this.sectionHeadings.length === 0) {
            return;
        }

        this.updateActiveSection = () => {
            const readingLine = Math.min(180, window.innerHeight * 0.25);
            const activeHeading = this.sectionHeadings.reduce((active, heading) => {
                return heading.getBoundingClientRect().top <= readingLine ? heading : active;
            }, this.sectionHeadings[0]);

            const anchor = activeHeading.querySelector('a[name], a[id], a[href^="#"]');
            const id = anchor.getAttribute('name') || anchor.id || anchor.hash.slice(1);
            const title = activeHeading.textContent.trim().replace(/^#\s*/, '');

            this.element.querySelectorAll('.anchors a').forEach((link) => {
                if (link.hash === `#${id}`) {
                    link.setAttribute('aria-current', 'location');
                } else {
                    link.removeAttribute('aria-current');
                }
            });

            this.currentSectionTargets.forEach((target) => {
                target.textContent = title;
            });
        };

        window.addEventListener('scroll', this.updateActiveSection, { passive: true });
        window.addEventListener('resize', this.updateActiveSection);
        this.updateActiveSection();
    }

    highlightSupportPolicyTable() {
        let highlightCells = (table) => {
            const currentDate = new Date().valueOf();

            Array.from(table.rows).forEach((row, rowIndex) => {
                if (rowIndex > 0) {
                    const cells = row.cells;
                    const versionCell = cells[0];
                    const bugDateCell = this.getCellDate(cells[cells.length - 2]);
                    const securityDateCell = this.getCellDate(cells[cells.length - 1]);

                    if (currentDate > securityDateCell) {
                        // End of life.
                        versionCell.classList.add('bg-danger', 'support-policy-highlight', 'bg-opacity-50');
                    } else if (currentDate <= securityDateCell && currentDate > bugDateCell) {
                        // Security fixes only.
                        versionCell.classList.add('bg-warning', 'support-policy-highlight', 'bg-opacity-50');
                    }
                }
            });
        };

        const table = document.querySelector('.documentations #support-policy ~ div table:first-of-type');

        if (table) {
            highlightCells(table);

            return;
        }

        // <=v9 documentation branches use the old dom structure which doesn't contain the table overflow fix.
        // It's easier to maintain backwards compatibility than to go back and change all the <=v9 branches.
        const oldTable =
            document.querySelector('.documentations #support-policy table') ||
            document.querySelector('.documentations table:first-of-type');

        if (oldTable) {
            highlightCells(oldTable);
        }
    }

    getCellDate(cell) {
        return Date.parse(cell.innerHTML.replace(/(\d+)(st|nd|rd|th)/, '$1'));
    }
}
