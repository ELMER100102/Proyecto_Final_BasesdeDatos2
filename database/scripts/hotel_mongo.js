/* =====================================================================
   IF0010 Bases de Datos II - Proyecto final
   Sistema de reservas de hotel - Base de datos MongoDB (hotel_mongo)

   Colecciones
   - resenas:  opiniones de los huéspedes después de su estancia.
   - mensajes: mensajes del formulario "Contáctenos" del sitio.
   Son datos que no mueven dinero ni disponibilidad, y cada documento puede
   traer campos distintos (por ejemplo, una reseña con o sin fotos).

   reservaId y huespedId guardan el Id de SQL Server; Laravel los relaciona.

   Ejecutar: mongosh hotel_mongo.js
   (o copiar y pegar en la pestaña mongosh de MongoDB Compass)
   ===================================================================== */
db = db.getSiblingDB("hotel_mongo");

// Empezar de cero
db.resenas.drop();
db.mensajes.drop();

/* ---------- Reseñas (reservas 1, 2 y 3 de SQL Server) ---------- */
db.resenas.insertMany([
  {
    reservaId: 1,
    huespedId: 3,
    huesped: "María Rodríguez",
    tipoHabitacion: "Estándar",
    calificacion: 5,
    comentario: "Muy limpio y el desayuno caribeño buenísimo.",
    fotos: ["/storage/resenas/1.jpg"],
    fecha: new Date("2026-09-20")
  },
  {
    reservaId: 2,
    huespedId: 4,
    huesped: "José Vargas",
    tipoHabitacion: "Superior",
    calificacion: 4,
    comentario: "Buena atención, el wifi era un poco lento.",
    fecha: new Date("2026-09-30")
  },
  {
    reservaId: 3,
    huespedId: 5,
    huesped: "Emily Johnson",
    tipoHabitacion: "Suite",
    calificacion: 5,
    comentario: "The ocean view was amazing!",
    fotos: ["/storage/resenas/3a.jpg", "/storage/resenas/3b.jpg"],
    respuestaHotel: "Thank you, Emily! We hope to see you again.",
    fecha: new Date("2026-10-12")
  }
]);

/* ---------- Mensajes de contacto ---------- */
db.mensajes.insertMany([
  {
    nombre: "Sofía Rojas",
    correo: "sofia@correo.test",
    asunto: "Reserva para un grupo",
    mensaje: "¿Tienen tarifa para 10 personas en enero?",
    respondido: false,
    fecha: new Date()
  },
  {
    nombre: "Diego Araya",
    correo: "diego@correo.test",
    telefono: "8800-2222",
    asunto: "Mascotas",
    mensaje: "¿Aceptan perros pequeños?",
    respondido: true,
    fecha: new Date()
  }
]);

/* ---------- Índices ---------- */
db.resenas.createIndex({ reservaId: 1 }, { unique: true });   // una reseña por reserva
db.resenas.createIndex({ tipoHabitacion: 1 });                // reseñas de un tipo de habitación
db.mensajes.createIndex({ respondido: 1, fecha: -1 });        // mensajes pendientes primero

/* ---------- Consultas de ejemplo (para la defensa) ---------- */
print("Reseñas con 5 estrellas:");
db.resenas.find({ calificacion: 5 }, { _id: 0, huesped: 1, comentario: 1 }).forEach(printjson);

print("Promedio de calificación por tipo de habitación:");
db.resenas.aggregate([
  { $group: { _id: "$tipoHabitacion", promedio: { $avg: "$calificacion" }, total: { $sum: 1 } } },
  { $sort: { promedio: -1 } }
]).forEach(printjson);

print("Mensajes sin responder:");
db.mensajes.find({ respondido: false }).forEach(printjson);

print("Marcar como respondido el mensaje de Sofía:");
db.mensajes.updateOne({ correo: "sofia@correo.test" }, { $set: { respondido: true } });

print("Total de documentos -> resenas: " + db.resenas.countDocuments() + ", mensajes: " + db.mensajes.countDocuments());
