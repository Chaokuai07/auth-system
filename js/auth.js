document.addEventListener('DOMContentLoaded', () => {
  console.log('JS ACTUALLY LOADED ON THIS PAGE!');
  console.log('Checking elements...');

  const birthdayInput = document.getElementById('birthday');
  if (birthdayInput) {
    birthdayInput.addEventListener('change', function () {
      if (this.value) this.classList.add('has-value');
      else this.classList.remove('has-value');
    });
    console.log('birthday handler bound');
  } else {
    console.log('no birthday input on this page');
  }

  function setupPasswordToggle(inputId, iconId) {
    const input = document.getElementById(inputId);
    const icon  = document.getElementById(iconId);

    if (!input || !icon) {
      console.log(`skip setup for ${inputId} / ${iconId} (not present)`);
      return;
    }

    input.addEventListener('input', () => {
      if (input.value.length > 0) {
        icon.classList.remove('fa-lock');
        icon.classList.add('fa-eye');
      } else {
        icon.classList.remove('fa-eye', 'fa-eye-slash');
        icon.classList.add('fa-lock');
        input.type = 'password';
      }
    });

    icon.addEventListener('click', (e) => {
      e.stopPropagation();
      if (input.value.length === 0) return;

      if (input.type === 'password') {
        input.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        input.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });

    console.log(`password toggle bound for ${inputId}`);
  }

  setupPasswordToggle('password', 'password-icon');
  setupPasswordToggle('login-password', 'login-password-icon');
});
