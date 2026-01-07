// ปุ่มไอคอนในเฮดเดอร์ (ตัวเดิม)
const searchIconBtn = document.querySelector('.search-icon');

// ชิ้นส่วนแผงใหม่
const panel = document.getElementById('searchPanel');
const overlay = document.getElementById('overlay');
const closeBtn = document.getElementById('searchClose');
const goBtn = document.getElementById('searchGo');
const inputPanel = document.getElementById('searchInputPanel');

function openSearch(){
  panel.classList.add('active');
  overlay.classList.add('active');
  document.body.classList.add('search-open');
  setTimeout(()=> inputPanel.focus(), 10);
}

function closeSearch(){
  panel.classList.remove('active');
  overlay.classList.remove('active');
  document.body.classList.remove('search-open');
}

function toggleSearch(){
  if(panel.classList.contains('active')) closeSearch();
  else openSearch();
}

function performSearch(q){
  const query = q.trim();
  if(!query) return;
  console.log('Searching for:', query);
  // 👉 ถ้าจะไปหน้าค้นหาจริง ใช้บรรทัดนี้:
  // window.location.href = `search.html?q=${encodeURIComponent(query)}`;
}

// click ไอคอนค้นหาใน header
searchIconBtn?.addEventListener('click', (e)=>{
  e.preventDefault();
  // ถ้า input มีข้อความอยู่แล้ว (เคยพิมพ์ค้าง) ให้ “ค้นหา” เลย
  if(panel.classList.contains('active') && inputPanel.value.trim() !== ''){
    performSearch(inputPanel.value);
  }else{
    openSearch();
  }
});

// ปุ่ม Go
goBtn.addEventListener('click', ()=> performSearch(inputPanel.value));

// Enter ในช่องค้นหา
inputPanel.addEventListener('keydown', (e)=>{
  if(e.key === 'Enter') performSearch(inputPanel.value);
});

// ปิดด้วยปุ่ม X / คลิกฉากดำ / กด ESC
closeBtn.addEventListener('click', closeSearch);
overlay.addEventListener('click', closeSearch);
document.addEventListener('keydown', (e)=> { if(e.key === 'Escape') closeSearch(); });

document.addEventListener("DOMContentLoaded", () => {
    const searchIcon = document.querySelector(".search-icon");
    if (searchIcon) {
        searchIcon.addEventListener("click", () => {
            console.log("Search clicked!");
        });
    }
});

function toggleMenu() {
    document.getElementById('sideMenu').classList.toggle('active');
}