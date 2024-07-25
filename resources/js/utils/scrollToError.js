import { animateScroll as scroll } from 'react-scroll';
export function scrollToError() {
  setTimeout(function () {
    const errorElement = document.querySelector('.error');

    if (errorElement) {
      const formGroupParent = errorElement.closest('.form-group');
      if (formGroupParent) {
        const errorPosition = formGroupParent.offsetTop;
        console.log(errorPosition);
        scroll.scrollTo(errorPosition, {
          duration: 500,
          smooth: 'easeInOutQuad',
        });
      }
    }
  }, 100);
}