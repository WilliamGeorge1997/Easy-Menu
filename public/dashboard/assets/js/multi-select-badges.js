(function () {
  function closestDir(el) {
    var node = el;
    while (node) {
      if (node.getAttribute && node.getAttribute('dir')) return node.getAttribute('dir');
      node = node.parentElement;
    }
    return document.documentElement.getAttribute('dir') || 'ltr';
  }

  function dispatchChange(selectEl) {
    try {
      var evt = new Event('change', { bubbles: true });
      selectEl.dispatchEvent(evt);
    } catch (e) {
      // IE fallback not needed; keep silent
    }
  }

  function build(selectEl) {
    if (!selectEl || selectEl.tagName !== 'SELECT') return;
    if (!selectEl.multiple) return;
    if (selectEl.dataset.msbReady === '1') return;
    if (selectEl.dataset.msb === 'off') return;

    selectEl.dataset.msbReady = '1';
    selectEl.classList.add('msb-native');

    var msb = document.createElement('div');
    msb.className = 'msb';
    msb.dir = closestDir(selectEl);

    var control = document.createElement('div');
    control.className = 'msb-control';
    control.tabIndex = 0;
    control.setAttribute('role', 'combobox');
    control.setAttribute('aria-expanded', 'false');

    var badges = document.createElement('div');
    badges.className = 'msb-badges';

    var placeholder = document.createElement('div');
    placeholder.className = 'msb-placeholder';
    placeholder.textContent = selectEl.getAttribute('placeholder') || selectEl.dataset.placeholder || 'Select...';

    var caret = document.createElement('div');
    caret.className = 'msb-caret';

    badges.appendChild(placeholder);
    control.appendChild(badges);
    control.appendChild(caret);

    var dropdown = document.createElement('div');
    dropdown.className = 'msb-dropdown';
    dropdown.setAttribute('role', 'listbox');

    msb.appendChild(control);
    msb.appendChild(dropdown);

    // Insert UI after the select (keeps input-group structure intact)
    selectEl.insertAdjacentElement('afterend', msb);

    function setDisabledState() {
      if (selectEl.disabled) {
        msb.classList.add('msb-disabled');
        control.tabIndex = -1;
      } else {
        msb.classList.remove('msb-disabled');
        control.tabIndex = 0;
      }
    }

    function open() {
      if (selectEl.disabled) return;
      msb.classList.add('msb-open');
      control.classList.add('msb-open');
      control.setAttribute('aria-expanded', 'true');
    }
    function close() {
      msb.classList.remove('msb-open');
      control.classList.remove('msb-open');
      control.setAttribute('aria-expanded', 'false');
    }
    function toggle() {
      if (msb.classList.contains('msb-open')) close();
      else open();
    }

    function renderBadges() {
      badges.innerHTML = '';
      var selected = Array.from(selectEl.options).filter(function (o) { return o.selected; });

      if (!selected.length) {
        badges.appendChild(placeholder);
        return;
      }

      selected.forEach(function (opt) {
        var b = document.createElement('span');
        b.className = 'msb-badge';

        var text = document.createElement('span');
        text.className = 'msb-badge-text';
        text.textContent = opt.text;

        var remove = document.createElement('button');
        remove.type = 'button';
        remove.className = 'msb-badge-remove';
        remove.setAttribute('aria-label', 'Remove');
        remove.innerHTML = '&times;';
        remove.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          if (selectEl.disabled) return;
          opt.selected = false;
          dispatchChange(selectEl);
          renderAll();
        });

        b.appendChild(text);
        b.appendChild(remove);
        badges.appendChild(b);
      });
    }

    function renderDropdown() {
      dropdown.innerHTML = '';

      Array.from(selectEl.options).forEach(function (opt) {
        if (opt.disabled && !opt.selected) {
          // still render disabled options, but keep them unclickable
        }

        var row = document.createElement('div');
        row.className = 'msb-option' + (opt.selected ? ' msb-option-selected' : '');
        row.setAttribute('role', 'option');
        row.setAttribute('aria-selected', opt.selected ? 'true' : 'false');

        if (opt.disabled) row.style.opacity = '0.6';

        var cb = document.createElement('input');
        cb.type = 'checkbox';
        cb.checked = !!opt.selected;
        cb.disabled = !!opt.disabled;

        var label = document.createElement('div');
        label.className = 'msb-option-label';
        label.textContent = opt.text;

        row.appendChild(cb);
        row.appendChild(label);

        row.addEventListener('click', function (e) {
          e.preventDefault();
          e.stopPropagation();
          if (selectEl.disabled) return;
          if (opt.disabled) return;
          opt.selected = !opt.selected;
          dispatchChange(selectEl);
          renderAll();
        });

        dropdown.appendChild(row);
      });
    }

    function renderAll() {
      setDisabledState();
      renderBadges();
      renderDropdown();
    }

    // Events
    control.addEventListener('click', function (e) {
      e.preventDefault();
      toggle();
    });

    control.addEventListener('keydown', function (e) {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        toggle();
      }
      if (e.key === 'Escape') {
        e.preventDefault();
        close();
      }
    });

    document.addEventListener('click', function (e) {
      if (!msb.contains(e.target) && e.target !== selectEl) close();
    });

    selectEl.addEventListener('change', function () {
      renderAll();
    });

    // Keep in sync when options are replaced dynamically (e.g. via fetch)
    try {
      var mo = new MutationObserver(function () { renderAll(); });
      mo.observe(selectEl, { childList: true, subtree: true, attributes: true, attributeFilter: ['disabled', 'selected'] });
    } catch (e) {}

    // Initial render
    renderAll();

    // Hide native select visually (kept for submit), but keep it in DOM order for validation
    selectEl.style.position = selectEl.style.position || 'absolute';
    selectEl.style.opacity = selectEl.style.opacity || '0';
    selectEl.style.pointerEvents = 'none';
    selectEl.style.width = '1px';
    selectEl.style.height = '1px';
    selectEl.style.margin = '0';
    selectEl.style.padding = '0';
  }

  function initAll() {
    document.querySelectorAll('select[multiple]').forEach(function (sel) {
      build(sel);
    });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
  } else {
    initAll();
  }
})();
