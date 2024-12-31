document.addEventListener("DOMContentLoaded", () => {
    const dropdowns = ["branch", "brand", "model", "year", "color", "turbo", "ccs"];

    dropdowns.forEach(dropdown => {
        fetch(`search_results.php?field=${dropdown}`)  // Ensure the URL is correct
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const selectElement = document.getElementById(dropdown);
                selectElement.innerHTML = '<option value="" disabled selected>Select an option</option>'; // Clear existing options
                data.forEach(value => {
                    const option = document.createElement("option");
                    option.value = value;
                    option.textContent = value;
                    selectElement.appendChild(option);
                });
            })
            .catch(error => console.error('Error fetching dropdown data:', error));
    });

    document.getElementById("search-form").addEventListener("submit", function (event) {
        event.preventDefault();
        const formData = new FormData(this);
        fetch("search_results.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(results => {
                const container = document.querySelector(".results-container");
                container.innerHTML = "";
                results.forEach(result => {
                    const div = document.createElement("div");
                    div.classList.add("result-item");
                    div.innerHTML = `
                        <h3>${result.brand} ${result.model}</h3>
                        <p><strong>Year:</strong> ${result.year}</p>
                        <p><strong>Color:</strong> ${result.color}</p>
                        <p><strong>Turbo:</strong> ${result.turbo ? 'Yes' : 'No'}</p>
                        <p><strong>CCs:</strong> ${result.ccs}</p>
                        <p><strong>Location:</strong> ${result.location}</p>
                        <p><strong>Price:</strong> $${result.price}</p>
                    `;
                    container.appendChild(div);
                });
            })
            .catch(error => console.error('Error fetching search results:', error));
    });
});
