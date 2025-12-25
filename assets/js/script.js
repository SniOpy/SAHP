// Scroll doux vers contact (future section)
document.querySelectorAll('.btn-primary').forEach((btn) => {
  btn.addEventListener('click', () => {
    alert('Simulation : ouverture formulaire devis');
  });
});

// Tracking simple (à brancher GA / Meta)
document.querySelectorAll('button').forEach((btn) => {
  btn.addEventListener('click', () => {
    console.log('CTA cliqué:', btn.textContent);
  });
});

// Ouverture du menu pliant mobile / tablette
const burger = document.querySelector('.burger');
const menu = document.querySelector('.nav-menu');

burger.addEventListener('click', () => {
  menu.classList.toggle('active');
});
