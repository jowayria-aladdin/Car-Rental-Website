function submitCarForm(formElement) {
    fetch('admin_add_car.php', {
        method: 'POST',
        body: new FormData(formElement)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Success: ' + data.message);
            formElement.reset(); // Reset form instead of page reload
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        alert('Error: An unexpected error occurred');
        console.error(error);
    });
}
