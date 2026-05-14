const navToggle = document.querySelector('.nav-toggle');
const mainNav = document.querySelector('#main-nav');

if (navToggle && mainNav) {
  navToggle.addEventListener('click', () => {
    const isOpen = mainNav.classList.toggle('open');
    navToggle.setAttribute('aria-expanded', String(isOpen));
  });
}

const productSelect = document.querySelector('#product_id');

if (productSelect) {
  productSelect.addEventListener('change', () => {
    const url = new URL(window.location.href);
    if (productSelect.value) {
      url.searchParams.set('product', productSelect.value);
    } else {
      url.searchParams.delete('product');
    }
    if (window.location.search !== url.search) {
      window.location.href = url.toString();
    }
  });
}
