
const usersBody = document.getElementById("usersBody");
const createUserForm = document.getElementById("createUserForm");
const formMessage = document.getElementById("formMessage");


function loadUsers() {
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

                const deleteButton = document.createElement("button");
                deleteButton.textContent = "Delete";

                deleteButton.addEventListener("click", function () {
                    const confirmed = confirm(
                        "Are you sure you want to delete " + user.name + "?"
                    );
                
                    if (!confirmed) {
                        return;
                    }
                
                    deleteUser(user.id);
                });

                deleteCell.appendChild(deleteButton);
                row.appendChild(deleteCell);



                const updateCell = document.createElement("td");

                const updateButton = document.createElement("button");
                updateButton.textContent = "Update";

                updateButton.addEventListener("click", function () {
                    document.getElementById("updateUserId").value = user.id;
                    document.getElementById("updateName").value = user.name;
                    document.getElementById("updateEmail").value = user.email;
                    document.getElementById("updatePassword").value = "";
                
                    document.getElementById("updateMessage").textContent = "";
                    document.getElementById("updateUserForm").hidden = false;
                });

                updateCell.appendChild(updateButton);
                row.appendChild(updateCell);


                usersBody.appendChild(row);
            });
        })
        .catch(function (error) {
            console.error("Error loading users:", error);
        });
}

createUserForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const name = document.getElementById("name").value.trim();
    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;

    fetch("api/users.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            name: name,
            email: email,
            password: password
        })
    })
        .then(function (response) {
            return response.json().then(function (result) {
                return {
                    status: response.status,
                    result: result
                };
            });
        })
        .then(function (responseData) {
            if (!responseData.result.success) {
                throw new Error(
                    responseData.result.message || "Could not create user."
                );
            }

            formMessage.textContent = responseData.result.message;
            formMessage.style.color = "green";

            createUserForm.reset();


            loadUsers();
        })
        .catch(function (error) {
            formMessage.textContent = error.message;
            formMessage.style.color = "red";
        });
});

const updateUserForm = document.getElementById("updateUserForm");
const updateMessage = document.getElementById("updateMessage");

updateUserForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const id = document.getElementById("updateUserId").value;
    const name = document.getElementById("updateName").value.trim();
    const email = document.getElementById("updateEmail").value.trim();
    const password = document.getElementById("updatePassword").value;

    const userData = {
        id: Number(id),
        name: name,
        email: email
    };


    if (password !== "") {
        userData.password = password;
    }

    fetch("api/users.php", {
        method: "PUT",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(userData)
    })
        .then(function (response) {
            return response.json().then(function (result) {
                return {
                    ok: response.ok,
                    result: result
                };
            });
        })
        .then(function (responseData) {
            if (!responseData.ok || !responseData.result.success) {
                throw new Error(
                    responseData.result.message || "Could not update user."
                );
            }

            updateMessage.textContent = responseData.result.message;
            updateMessage.style.color = "green";

            updateUserForm.hidden = true;

            loadUsers();
        })
        .catch(function (error) {
            updateMessage.textContent = error.message;
            updateMessage.style.color = "red";
        });
});

document.getElementById("cancelUpdate").addEventListener("click", function () {
    updateUserForm.hidden = true;
    updateMessage.textContent = "";
});

function deleteUser(id) {
    fetch("api/users.php", {
        method: "DELETE",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            id: Number(id)
        })
    })
        .then(function (response) {
            return response.json().then(function (result) {
                return {
                    ok: response.ok,
                    result: result
                };
            });
        })
        .then(function (responseData) {
            if (!responseData.ok || !responseData.result.success) {
                throw new Error(
                    responseData.result.message || "Could not delete user."
                );
            }

            loadUsers();
        })
        .catch(function (error) {
            alert(error.message);
        });
}

loadUsers();
