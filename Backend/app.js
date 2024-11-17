const express = require("express");
const morgan = require("morgan");
const db = require("./config/db.js");

const app = express();

app.use(morgan());
app.use(express.json());
/* 
app.get("/", (req, res) => {
  db.query("SELECT 1 + 1 AS resultado")
    .then(([rows]) => {
      res.send(
        `La conexión funciona: resultado de 1 + 1 = ${rows[0].resultado}`
      );
    })
    .catch((err) => {
      res.status(500).send("Error al ejecutar la consulta");
    });
}); */

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`Servidor en puerto ${PORT}`);
});
