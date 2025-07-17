// /Shakti/peticiones(js)/firebaseInit.js
import { initializeApp } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-app.js";
import { getDatabase } from "https://www.gstatic.com/firebasejs/11.10.0/firebase-database.js";

const firebaseConfig = {
  apiKey: "AIzaSyANqVJvYR4AzFR4XM9qY2DNi8pv3VFmLF0",
  authDomain: "shakti-b4ace.firebaseapp.com",
  projectId: "shakti-b4ace",
  storageBucket: "shakti-b4ace.appspot.com",
  messagingSenderId: "346097573264",
  appId: "1:346097573264:web:fbd683dd475f8d3d8aa715",
  databaseURL: "https://shakti-b4ace-default-rtdb.firebaseio.com"
};

const app = initializeApp(firebaseConfig);
const db = getDatabase(app);

export { app, db };
