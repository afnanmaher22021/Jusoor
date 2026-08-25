document.addEventListener('DOMContentLoaded', function () {
  // Mobile menu toggle
  var toggle = document.querySelector('.menu-toggle');
  var links = document.querySelector('.nav-links');
  if (toggle && links) {
    toggle.addEventListener('click', function () {
      links.classList.toggle('open');
    });
  }

  // Auto-dismiss flash messages
  document.querySelectorAll('.flash').forEach(function (el) {
    setTimeout(function () {
      el.style.transition = 'opacity .4s';
      el.style.opacity = '0';
      setTimeout(function () { el.remove(); }, 400);
    }, 5000);
  });

  // Multi-step register: role selection
  var roleInputs = document.querySelectorAll('input[name="role"]');
  var roleCards = document.querySelectorAll('.role-card');
  var orgFields = document.getElementById('org-fields');

  function updateOrgFieldsState(role) {
    if (orgFields) {
      orgFields.style.display = (role === 'organization') ? 'block' : 'none';
      orgFields.querySelectorAll('input, textarea').forEach(function (f) {
        f.disabled = (role !== 'organization');
      });
    }
  }

  // Initial state check
  var initialRoleInput = document.querySelector('input[name="role"]');
  if (initialRoleInput) {
    updateOrgFieldsState(initialRoleInput.value);
  }

  roleCards.forEach(function (card) {
    card.addEventListener('click', function () {
      var role = card.dataset.role;
      roleCards.forEach(function (c) { c.classList.remove('selected'); });
      card.classList.add('selected');
      roleInputs.forEach(function (i) {
        i.value = role;
        i.checked = true;
      });
      updateOrgFieldsState(role);
    });
  });

  // Confirm delete forms
  document.querySelectorAll('form[data-confirm]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
      if (!confirm(form.dataset.confirm)) e.preventDefault();
    });
  });
});
