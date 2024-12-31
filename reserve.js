document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = ["brand", "year", "color", "ccs", "location"];

    dropdowns.forEach(dropdown => {
        fetch(`reservecar.php?field=${dropdown}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Failed to fetch ${dropdown}: ${response.statusText}`);
                }
                return response.json();
            })
            .then(data => {
                const selectElement = document.getElementById(dropdown);
                selectElement.innerHTML = '<option value="" disabled selected>Select an option</option>';

                if (Array.isArray(data) && data.length > 0) {
                    data.forEach(value => {
                        const option = document.createElement("option");
                        option.value = value;
                        option.textContent = value;
                        selectElement.appendChild(option);
                    });
                } else {
                    console.warn(`No data available for ${dropdown}`);
                }
            })
            .catch(error => console.error(`Error fetching data for ${dropdown}:`, error));
    });

    const turboSelect = document.getElementById("turbo");
    turboSelect.innerHTML = '<option value="" disabled selected>Select option</option>';
    ['yes', 'no'].forEach(value => {
        const option = document.createElement("option");
        option.value = value;
        option.textContent = value.charAt(0).toUpperCase() + value.slice(1);
        turboSelect.appendChild(option);
    });

    // Fetch car models independently
    fetch(`reservecar.php?field=model`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to fetch car_model: ${response.statusText}`);
            }
            return response.json();
        })
        .then(data => {
            const modelSelect = document.getElementById("model");
            modelSelect.innerHTML = '<option value="" disabled selected>Select a model</option>';

            data.forEach(model => {
                const option = document.createElement("option");
                option.value = model;
                option.textContent = model;
                modelSelect.appendChild(option);
            });
        })
        .catch(error => console.error('Error fetching car models:', error));

    document.getElementById("reserveform").addEventListener("submit", function (event) {
        event.preventDefault();

        // Validate dates
        const pickupDate = new Date(document.getElementById("pickup_date").value);
        const returnDate = new Date(document.getElementById("return_date").value);
        const today = new Date();

        // Check if pickup date is in the future
        if (pickupDate <= today) {
            alert("Pickup date must be in the future.");
            return;
        }

        // Check if return date is after pickup date
        if (returnDate <= pickupDate) {
            alert("Return date must be after the pickup date.");
            return;
        }

        const formData = new FormData(this);

        // Submit form if validation passes
        fetch("reservecar.php", {
            method: "POST",
            body: formData
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Failed to submit reservation: ${response.statusText}`);
            }
            return response.json();
        })
        .then(results => {
            if (results.success) {
                alert('Car reserved successfully!');
            } else if (results.error) {
                alert(`Error: ${results.error}`);
            }
        })
        .catch(error => {
            console.error('Error submitting form:', error);
            alert(`There was an error processing your reservation. Please try again.\nDetails: ${error.message}`);
        });
    });
});
