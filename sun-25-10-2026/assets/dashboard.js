
const usersBody = document.getElementById("usersBody");

fetch("api/users.php")
    .then(function (response) {
        return response.json();
    })
    .then(function (result) {
        
        if (!result.success) {
            throw new Error(result.message || "Could not load users.");
        }

        usersBody.innerHTML = "";

        result.data.forEach(function (user) {

            const row = document.createElement("tr");

            const nameCell = document.createElement("td");
            nameCell.textContent = user.name;
            row.appendChild(nameCell);
            const emailCell = document.createElement("td");
            emailCell.textContent = user.email;
            row.appendChild(emailCell);


            const deleteCell = document.createElement("td");
            row.appendChild(deleteCell);

            const updateCell = document.createElement("td");
            row.appendChild(updateCell);

  
            usersBody.appendChild(row);
        });
    })
    .catch(function (error) {
        console.error("Error loading users:", error);
    });
