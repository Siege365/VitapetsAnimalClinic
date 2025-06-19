import { initializeApp } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-app.js";
import { getAuth, GoogleAuthProvider, signInWithPopup } from "https://www.gstatic.com/firebasejs/11.6.1/firebase-auth.js";

const firebaseConfig = {
  apiKey: "AIzaSyAZufKugokV0L2Hg6_t5sNo3l5IXHoUwzI",
  authDomain: "vitapetsanimalclinic-login.firebaseapp.com",
  projectId: "vitapetsanimalclinic-login",
  storageBucket: "vitapetsanimalclinic-login.firebasestorage.app",
  messagingSenderId: "700426312029",
  appId: "1:700426312029:web:bfe136d81733330bb770b4"
};

   // Initialize Firebase
  const app = initializeApp(firebaseConfig);
  const auth = getAuth(app);
  auth.languageCode = 'en';
  const provider = new GoogleAuthProvider();

  const googleLogin = document.getElementById("googleLogin");
  googleLogin.addEventListener("click", function() {
    signInWithPopup(auth, provider)
      .then((result) => {
        const user = result.user;
        // Send user info to PHP to create a session
        fetch("../php/google_session.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
                email: user.email,
                name: user.displayName,
                photo: user.photoURL
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.status === "success") {
                window.location.href = "../html/mainpage.php";
            } else {
                alert("Server login failed.");
            }
        });
    })
  });
