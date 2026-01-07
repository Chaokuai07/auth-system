document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('reportSearch');
  const table = document.getElementById('reportTable');
  const tbody = table.querySelector('tbody');
  const rows = Array.from(tbody.querySelectorAll('tr'));
  const countEl = document.getElementById('reportCount');

  function normalize(s) {
    return s.trim().toLowerCase();
  }

  function updateCount(visible) {
    countEl.textContent = `ผลลัพธ์: ${visible} รายการ`;
  }

  input.addEventListener('input', () => {
    const q = normalize(input.value);
    if (q === '') {
      rows.forEach(r => r.style.display = '');
      updateCount(rows.length);
      return;
    }

    let visible = 0;
    rows.forEach(r => {
      const brand = normalize(r.querySelector('.col-brand').textContent);
      const model = normalize(r.querySelector('.col-model').textContent);
      if (brand.includes(q) || model.includes(q)) {
        r.style.display = '';
        visible++;
      } else {
        r.style.display = 'none';
      }
    });
    updateCount(visible);
  });

  // initial
  updateCount(rows.length);
});