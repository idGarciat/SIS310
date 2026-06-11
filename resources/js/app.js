document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll('form').forEach((form) => {
		const firstField = form.querySelector('input, textarea, select');
		if (firstField && form.hasAttribute('data-autofocus-first-input')) {
			firstField.focus();
		}
	});
});
