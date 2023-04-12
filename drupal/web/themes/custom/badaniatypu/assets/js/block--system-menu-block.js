((Drupal) => {
  Drupal.behaviors.badaniatypuMenuBlock = {
    attach: () => {
      const menuToggle = document.querySelector(".block-menu .menu__toggle");
      const menu = document.querySelector(".block-menu .menu");
      menuToggle.addEventListener("click", () =>
        menu.classList.toggle("active")
      );
    },
  };
})(Drupal);
