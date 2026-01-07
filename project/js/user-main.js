// user-main.js
document.addEventListener('DOMContentLoaded', ()=>{

  // helper - ถ้ามี API ให้เปลี่ยน endpoint below
  const API_TOGGLE_FAV = '/user/toggle_fav.php'; // optional
  const API_CART = '/user/cart_action.php'; // optional
  const API_BOOK = '/user/book_action.php'; // optional

  // Toggle heart
  document.querySelectorAll('.fav-btn').forEach(btn=>{
    // initial: check localStorage
    const id = btn.dataset.id;
    const key = 'fav_'+id;
    if (localStorage.getItem(key) === '1') {
      btn.classList.add('active');
      btn.querySelector('i').classList.remove('fa-regular'); btn.querySelector('i').classList.add('fa-solid');
    }

    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      const id = btn.dataset.id;
      const key = 'fav_'+id;
      const isActive = btn.classList.toggle('active');

      // icon change
      const icon = btn.querySelector('i');
      if (isActive) {
        icon.classList.remove('fa-regular'); icon.classList.add('fa-solid');
        localStorage.setItem(key,'1');
      } else {
        icon.classList.remove('fa-solid'); icon.classList.add('fa-regular');
        localStorage.removeItem(key);
      }

      // optional: send to backend if logged in
      try {
        await fetch(API_TOGGLE_FAV, {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({ product_id: id, fav: isActive ? 1 : 0 })
        });
      } catch(err){
        // ignore if no endpoint — we use localStorage
      }
    });
  });

  // Cart button behavior (simulate + visual)
  document.querySelectorAll('.btn-cart').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      const id = btn.dataset.id;
      // toggle in localStorage cart
      const key = 'cart_'+id;
      const inCart = localStorage.getItem(key) === '1';
      if (inCart) {
        localStorage.removeItem(key);
        btn.classList.remove('added');
        btn.textContent = '🛒';
      } else {
        localStorage.setItem(key,'1');
        btn.classList.add('added');
        btn.textContent = '✓';
      }

      // optional: send to backend
      try {
        await fetch(API_CART, {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({ product_id:id, action: inCart ? 'remove' : 'add' })
        });
      } catch(err){}
    });

    // set initial visual
    const key = 'cart_'+btn.dataset.id;
    if (localStorage.getItem(key) === '1') {
      btn.classList.add('added'); btn.textContent = '✓';
    }
  });

  // Book button behavior (simulate reservation)
  document.querySelectorAll('.btn-book').forEach(btn=>{
    btn.addEventListener('click', async (e)=>{
      e.preventDefault();
      const id = btn.dataset.id;
      const key = 'book_'+id;
      const booked = localStorage.getItem(key) === '1';
      if (booked) {
        localStorage.removeItem(key);
        btn.classList.remove('booked');
        btn.textContent = 'จอง';
        alert('ยกเลิกการจองเรียบร้อย');
      } else {
        localStorage.setItem(key,'1');
        btn.classList.add('booked');
        btn.textContent = 'จองแล้ว';
        alert('จองสำเร็จ (จำลอง)');
      }

      // optional: send to backend
      try {
        await fetch(API_BOOK, {
          method:'POST',
          headers:{'Content-Type':'application/json'},
          body: JSON.stringify({ product_id:id, action: booked ? 'unbook' : 'book' })
        });
      } catch(err){}
    });

    // initial
    if (localStorage.getItem('book_'+btn.dataset.id) === '1') {
      btn.classList.add('booked'); btn.textContent = 'จองแล้ว';
    }
  });

});