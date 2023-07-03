export function showToast(message, type) {
    this.$toasted.show(message, {
      theme: 'toasted-primary',
      position: 'top-center',
      duration: 3000,
      type: type,
    });
  }