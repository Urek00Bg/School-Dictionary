const q = document.getElementById('q');
const btn = document.getElementById('btnSearch');
const cardContainer = document.getElementById('cardContainer');
const gradeSelect = document.getElementById('gradeSelect');

let selectedGrade = null; // 👈 start as null, means no filter yet

function escapeHtml(s) {
  return (s ?? '').toString()
    .replace(/&/g, '&amp;')
    .replace(/</g, '&lt;')
    .replace(/>/g, '&gt;')
    .replace(/"/g, '&quot;')
    .replace(/'/g, '&#039;');
}

function render(rows) {
  if (!rows.length) {
    cardContainer.innerHTML = `<div class="col-12 text-center text-muted">No results found</div>`;
    return;
  }

  cardContainer.innerHTML = rows.map(r => `
    <div class="col d-flex">
      <div class="card shadow-sm flex-fill h-100">
        <div class="card-body d-flex flex-column">
          <h5 class="card-title mb-1">${escapeHtml(r.word)} </h5>
          <p class="h5 word_sl pt-1 pb-1"> ${escapeHtml(r.word_sl)}</p>
          
          <p class="card-text small mb-2 p-1">${escapeHtml(r.definition)}</p>
          <p class="card-text small fst-italic mb-2 p-1">${escapeHtml(r.definition_sl)}</p>
          <div class="grade mt-auto text-muted small">Grade: ${escapeHtml(r.grade)}</div>
        </div>
      </div>
    </div>
  `).join('');
}


async function search() {
  const term = q.value.trim();
  
  // 👇 Only include grade if user actually selected something
  let url = 'includes/webincludes/search_api.php?q=' + encodeURIComponent(term);
  if (selectedGrade && selectedGrade !== "0") {
    url += '&grade=' + encodeURIComponent(selectedGrade);
  }

  const res = await fetch(url);
  const data = await res.json();
  render(data);
}

let timer;
q.addEventListener('input', () => {
  clearTimeout(timer);
  timer = setTimeout(search, 300);
});
btn.addEventListener('click', search);

// 🎓 Dropdown filter — only filters after user selects
gradeSelect.addEventListener('change', () => {
  selectedGrade = gradeSelect.value;
  search();
});

// Initial load — show all words (no grade filter)
search();
