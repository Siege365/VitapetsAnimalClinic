// Import the functions you need from the SDKs you need
                import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
                import { getDatabase, ref, set, get, child } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-database.js";

                // Your web app's Firebase configuration
                const firebaseConfig = {
                apiKey: "AIzaSyAZufKugokV0L2Hg6_t5sNo3l5IXHoUwzI",
                authDomain: "vitapetsanimalclinic-login.firebaseapp.com",
                databaseURL: "https://vitapetsanimalclinic-login-default-rtdb.asia-southeast1.firebasedatabase.app/",
                projectId: "vitapetsanimalclinic-login",
                storageBucket: "vitapetsanimalclinic-login.firebasestorage.app",
                messagingSenderId: "700426312029",
                appId: "1:700426312029:web:bfe136d81733330bb770b4"
                };

                // Initialize Firebase
                const app = initializeApp(firebaseConfig);
                const database = getDatabase(app);

                document.getElementById("submit").addEventListener("click", function (e) {
                e.preventDefault();

                const userEmail = document.getElementById("useremail").value;
                const userFname = document.getElementById("userfname").value;
                const userLname = document.getElementById("userlname").value;
                const userPassword = document.getElementById("userpassword").value;
                const userPhone = document.getElementById("userphone").value;

                // Reference to the database
                const dbRef = ref(database);

                // Check if the email already exists
                get(child(dbRef, `Customers/`)).then((snapshot) => {
                    if (snapshot.exists()) {
                    const customers = snapshot.val();
                    let emailExists = false;

                    // Loop through the customers to check if the email exists
                    for (const key in customers) {
                        if (customers[key].email === userEmail) {
                        emailExists = true;
                        break;
                        }
                    }

                    if (emailExists) {
                        alert("Account with this email already exists!");
                    } else {
                        // If email does not exist, create the account
                        set(ref(database, 'Customers/' + userFname), {
                        userfname: userFname,
                        userlname: userLname,
                        password: userPassword,
                        email: userEmail,
                        phone: userPhone
                        }).then(() => {
                        alert("Account Created Successfully");
                        window.location.href = "login.html";
                        }).catch((error) => {
                        console.error("Error creating account:", error);
                        });
                    }
                    } else {
                    // If no customers exist, create the first account
                    set(ref(database, 'Customers/' + userFname), {
                        userfname: userFname,
                        userlname: userLname,
                        password: userPassword,
                        email: userEmail,
                        phone: userPhone
                    }).then(() => {
                        alert("Account Created Successfully");
                        window.location.href = "login.html";
                    }).catch((error) => {
                        console.error("Error creating account:", error);
                    });
                    }
                }).catch((error) => {
                    console.error("Error checking account existence:", error);
                });
                });