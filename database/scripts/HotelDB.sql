CREATE DATABASE HotelDB;
GO
USE HotelDB;
GO

-- Información del hotel para el sitio web
CREATE TABLE Hotel (
    HotelId INT IDENTITY(1,1) PRIMARY KEY,
    Nombre NVARCHAR(100) NOT NULL,
    Descripcion NVARCHAR(500) NULL,
    LogoUrl  NVARCHAR(255) NULL,
    Direccion NVARCHAR(255) NOT NULL,
    Latitud DECIMAL(9,6)  NULL,
    Longitud DECIMAL(9,6)  NULL,
    Telefono VARCHAR(20)   NOT NULL,
    Correo VARCHAR(100)  NOT NULL,
    PorcentajeImpuesto DECIMAL(5,2)  NOT NULL DEFAULT 13
        CHECK (PorcentajeImpuesto BETWEEN 0 AND 100)
);

-- imagenes para el sitio web
CREATE TABLE ImagenSitio (
    ImagenId INT IDENTITY(1,1) PRIMARY KEY,
    HotelId INT NOT NULL REFERENCES Hotel(HotelId),
    Tipo VARCHAR(10)   NOT NULL CHECK (Tipo IN ('SLIDE', 'GALERIA')),
    Titulo NVARCHAR(100) NULL,
    ImagenUrl NVARCHAR(255) NOT NULL,
    Orden TINYINT NOT NULL DEFAULT 1,
    Activo BIT NOT NULL DEFAULT 1
);

CREATE TABLE Persona (
    PersonaId INT IDENTITY(1,1) PRIMARY KEY,
    Cedula VARBINARY(256) NOT NULL, -- campo encriptado
    Nombre NVARCHAR(60)   NOT NULL,
    Apellidos NVARCHAR(100)  NOT NULL,
    Correo VARCHAR(100)   NOT NULL UNIQUE,
    Telefono VARBINARY(256) NULL, -- campo encriptado
    FechaRegistro DATETIME2(0)   NOT NULL DEFAULT SYSDATETIME()
);

CREATE TABLE Huesped (
    PersonaId INT PRIMARY KEY REFERENCES Persona(PersonaId),
    Nacionalidad NVARCHAR(30) NOT NULL DEFAULT 'Costa Rica'
);

CREATE TABLE Empleado (
    PersonaId INT PRIMARY KEY REFERENCES Persona(PersonaId),
    Puesto VARCHAR(20)   NOT NULL CHECK (Puesto IN ('ADMINISTRADOR', 'RECEPCIONISTA')),
    Salario DECIMAL(12,2) NOT NULL CHECK (Salario > 0)
);

-- cada persona solo puede tener un usuario
CREATE TABLE Usuario (
    UsuarioId INT IDENTITY(1,1) PRIMARY KEY,
    PersonaId INT NOT NULL UNIQUE REFERENCES Persona(PersonaId),
    Rol VARCHAR(20) NOT NULL CHECK (Rol IN ('ADMINISTRADOR', 'RECEPCIONISTA', 'HUESPED')),
    ContrasenaHash VARCHAR(255) NOT NULL, --hash bcrypt generado por Laravel
    Activo BIT NOT NULL DEFAULT 1
);

CREATE TABLE TipoHabitacion (
    TipoHabitacionId  INT IDENTITY(1,1) PRIMARY KEY,
    Nombre   NVARCHAR(60)  NOT NULL UNIQUE,
    Descripcion NVARCHAR(500) NULL,
    Capacidad TINYINT NOT NULL CHECK (Capacidad > 0),
    PrecioNoche DECIMAL(12,2) NOT NULL CHECK (PrecioNoche > 0),
    FotoUrl NVARCHAR(255) NULL,
    Activo BIT NOT NULL DEFAULT 1
);

CREATE TABLE Habitacion (
    HabitacionId INT IDENTITY(1,1) PRIMARY KEY,
    TipoHabitacionId  INT NOT NULL REFERENCES TipoHabitacion(TipoHabitacionId),
    Numero VARCHAR(10) NOT NULL UNIQUE,
    Piso  TINYINT  NOT NULL,
    Estado VARCHAR(15) NOT NULL DEFAULT 'DISPONIBLE'
        CHECK (Estado IN ('DISPONIBLE', 'OCUPADA', 'SUCIA', 'MANTENIMIENTO'))
);

CREATE TABLE Reserva (
    ReservaId INT IDENTITY(1,1) PRIMARY KEY,
    HuespedId INT NOT NULL REFERENCES Huesped(PersonaId),
    FechaEntrada DATE NOT NULL,
    FechaSalida DATE NOT NULL,
    CantPersonas TINYINT NOT NULL CHECK (CantPersonas > 0),
    Estado VARCHAR(12) NOT NULL DEFAULT 'PENDIENTE'
        CHECK (Estado IN ('PENDIENTE', 'CONFIRMADA', 'EN_CURSO', 'FINALIZADA', 'CANCELADA')),
    Total DECIMAL(12,2) NOT NULL DEFAULT 0,
    FechaReserva DATETIME2(0)  NOT NULL DEFAULT SYSDATETIME(),
    CONSTRAINT CK_Reserva_Fechas CHECK (FechaSalida > FechaEntrada)
);

-- Relación N:M entre Reserva y Habitacion, con atributos
CREATE TABLE DetalleReserva (
    ReservaId INT NOT NULL REFERENCES Reserva(ReservaId),
    HabitacionId INT NOT NULL REFERENCES Habitacion(HabitacionId),
    Noches TINYINT  NOT NULL CHECK (Noches > 0),
    PrecioNoche  DECIMAL(12,2) NOT NULL, -- precio pactado al reservar
    Subtotal DECIMAL(12,2) NOT NULL,
    PRIMARY KEY (ReservaId, HabitacionId)
);

CREATE TABLE Servicio (
    ServicioId  INT IDENTITY(1,1) PRIMARY KEY,
    Nombre NVARCHAR(80)  NOT NULL UNIQUE,
    Precio DECIMAL(12,2) NOT NULL CHECK (Precio > 0),
    Activo BIT NOT NULL DEFAULT 1
);

-- Relación N:M entre Reserva y Servicio, con atributos (consumos)
CREATE TABLE Cargo (
    CargoId INT IDENTITY(1,1) PRIMARY KEY,
    ReservaId INT NOT NULL REFERENCES Reserva(ReservaId),
    ServicioId INT NOT NULL REFERENCES Servicio(ServicioId),
    Cantidad INT NOT NULL CHECK (Cantidad > 0),
    PrecioUnitario DECIMAL(12,2) NOT NULL,
    Fecha DATETIME2(0)  NOT NULL DEFAULT SYSDATETIME()
);

CREATE TABLE Pago (
    PagoId INT IDENTITY(1,1) PRIMARY KEY,
    ReservaId  INT NOT NULL REFERENCES Reserva(ReservaId),
    Tipo VARCHAR(10)   NOT NULL CHECK (Tipo IN ('ADELANTO', 'PAGO', 'REEMBOLSO')),
    Metodo VARCHAR(15)   NOT NULL CHECK (Metodo IN ('EFECTIVO', 'TARJETA', 'SINPE')),
    Monto DECIMAL(12,2) NOT NULL CHECK (Monto > 0),
    Fecha DATETIME2(0)  NOT NULL DEFAULT SYSDATETIME()
);

-- Historial que escriben los triggers
CREATE TABLE Auditoria (
    AuditoriaId  INT IDENTITY(1,1) PRIMARY KEY,
    Tabla VARCHAR(30) NOT NULL,
    Operacion CHAR(1) NOT NULL CHECK (Operacion IN ('I', 'U', 'D')),
    Datos NVARCHAR(MAX) NULL,
    Usuario NVARCHAR(128) NOT NULL DEFAULT SUSER_SNAME(),
    Fecha  DATETIME2(0)  NOT NULL DEFAULT SYSDATETIME()
);
GO

--2. INDICES (consultas más comunes)
CREATE INDEX IX_Reserva_Fechas ON Reserva (Estado, FechaEntrada, FechaSalida);  -- disponibilidad y llegadas
CREATE INDEX IX_Reserva_Huesped ON Reserva (HuespedId); -- "mis reservas"
CREATE INDEX IX_Detalle_Habitacion ON DetalleReserva (HabitacionId);-- reservas de una habitación
CREATE INDEX IX_Habitacion_Tipo ON Habitacion (TipoHabitacionId);  -- habitaciones de un tipo
CREATE INDEX IX_Cargo_Reserva ON Cargo (ReservaId);  -- cuenta de la reserva
CREATE INDEX IX_Pago_Reserva ON Pago (ReservaId); -- pagos de la reserva
GO


--3. ENCRIPTACIÓN
--Se encriptan la cédula y el teléfono de Persona con una llave AES-256.
--Solo los procedimientos sp_RegistrarPersona y sp_ObtenerPersona abren la llave.
-- Las contraseñas no se encriptan: Laravel guarda un hash bcrypt.

CREATE MASTER KEY ENCRYPTION BY PASSWORD = 'Hotel#Clave2026';
CREATE CERTIFICATE CertHotel WITH SUBJECT = 'Datos personales HotelDB';
CREATE SYMMETRIC KEY LlaveHotel WITH ALGORITHM = AES_256 ENCRYPTION BY CERTIFICATE CertHotel;
GO

--4. PROCEDIMIENTOS BÁSICOS (CRUD)
--7 de 14 tablas: Hotel, ImagenSitio, TipoHabitacion, Habitacion,
-- Servicio, Usuario y Cargo. Obtener con @Id NULL devuelve todas las filas.

--HOTEL
CREATE OR ALTER PROCEDURE sp_Hotel_Insertar
    @Nombre NVARCHAR(100), @Descripcion NVARCHAR(500), @LogoUrl NVARCHAR(255), @Direccion NVARCHAR(255),
    @Latitud DECIMAL(9,6), @Longitud DECIMAL(9,6), @Telefono VARCHAR(20), @Correo VARCHAR(100),
    @PorcentajeImpuesto DECIMAL(5,2) = 13
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO Hotel (Nombre, Descripcion, LogoUrl, Direccion, Latitud, Longitud, Telefono, Correo, PorcentajeImpuesto)
        VALUES (@Nombre, @Descripcion, @LogoUrl, @Direccion, @Latitud, @Longitud, @Telefono, @Correo, @PorcentajeImpuesto);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS HotelId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
CREATE OR ALTER PROCEDURE sp_Hotel_Obtener @HotelId INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT * FROM Hotel WHERE @HotelId IS NULL OR HotelId = @HotelId;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Hotel_Actualizar
    @HotelId INT, @Nombre NVARCHAR(100), @Descripcion NVARCHAR(500), @LogoUrl NVARCHAR(255), @Direccion NVARCHAR(255),
    @Latitud DECIMAL(9,6), @Longitud DECIMAL(9,6), @Telefono VARCHAR(20), @Correo VARCHAR(100),
    @PorcentajeImpuesto DECIMAL(5,2)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE Hotel
        SET Nombre = @Nombre, Descripcion = @Descripcion, LogoUrl = @LogoUrl, Direccion = @Direccion,
            Latitud = @Latitud, Longitud = @Longitud, Telefono = @Telefono, Correo = @Correo,
            PorcentajeImpuesto = @PorcentajeImpuesto
        WHERE HotelId = @HotelId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El hotel no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Hotel_Eliminar @HotelId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM Hotel WHERE HotelId = @HotelId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El hotel no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--IMAGEN
CREATE OR ALTER PROCEDURE sp_ImagenSitio_Insertar
    @HotelId INT, @Tipo VARCHAR(10), @Titulo NVARCHAR(100), @ImagenUrl NVARCHAR(255), @Orden TINYINT = 1, @Activo BIT = 1
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO ImagenSitio (HotelId, Tipo, Titulo, ImagenUrl, Orden, Activo)
        VALUES (@HotelId, @Tipo, @Titulo, @ImagenUrl, @Orden, @Activo);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS ImagenId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_ImagenSitio_Obtener @ImagenId INT = NULL, @Tipo VARCHAR(10) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT * FROM ImagenSitio
        WHERE (@ImagenId IS NULL OR ImagenId = @ImagenId) AND (@Tipo IS NULL OR Tipo = @Tipo)
        ORDER BY Tipo, Orden;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_ImagenSitio_Actualizar
    @ImagenId INT, @Tipo VARCHAR(10), @Titulo NVARCHAR(100), @ImagenUrl NVARCHAR(255), @Orden TINYINT, @Activo BIT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE ImagenSitio
        SET Tipo = @Tipo, Titulo = @Titulo, ImagenUrl = @ImagenUrl, Orden = @Orden, Activo = @Activo
        WHERE ImagenId = @ImagenId;
        IF @@ROWCOUNT = 0 THROW 50000, 'La imagen no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_ImagenSitio_Eliminar @ImagenId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM ImagenSitio WHERE ImagenId = @ImagenId;
        IF @@ROWCOUNT = 0 THROW 50000, 'La imagen no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--TIPO HABITACION
CREATE OR ALTER PROCEDURE sp_TipoHabitacion_Insertar
    @Nombre NVARCHAR(60), @Descripcion NVARCHAR(500), @Capacidad TINYINT, @PrecioNoche DECIMAL(12,2),
    @FotoUrl NVARCHAR(255) = NULL, @Activo BIT = 1
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO TipoHabitacion (Nombre, Descripcion, Capacidad, PrecioNoche, FotoUrl, Activo)
        VALUES (@Nombre, @Descripcion, @Capacidad, @PrecioNoche, @FotoUrl, @Activo);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS TipoHabitacionId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_TipoHabitacion_Obtener @TipoHabitacionId INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT * FROM TipoHabitacion
        WHERE @TipoHabitacionId IS NULL OR TipoHabitacionId = @TipoHabitacionId
        ORDER BY PrecioNoche;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_TipoHabitacion_Actualizar
    @TipoHabitacionId INT, @Nombre NVARCHAR(60), @Descripcion NVARCHAR(500), @Capacidad TINYINT,
    @PrecioNoche DECIMAL(12,2), @FotoUrl NVARCHAR(255), @Activo BIT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE TipoHabitacion
        SET Nombre = @Nombre, Descripcion = @Descripcion, Capacidad = @Capacidad,
            PrecioNoche = @PrecioNoche, FotoUrl = @FotoUrl, Activo = @Activo
        WHERE TipoHabitacionId = @TipoHabitacionId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El tipo de habitación no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_TipoHabitacion_Eliminar @TipoHabitacionId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM TipoHabitacion WHERE TipoHabitacionId = @TipoHabitacionId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El tipo de habitación no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--HABITACION
CREATE OR ALTER PROCEDURE sp_Habitacion_Insertar
    @TipoHabitacionId INT, @Numero VARCHAR(10), @Piso TINYINT, @Estado VARCHAR(15) = 'DISPONIBLE'
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO Habitacion (TipoHabitacionId, Numero, Piso, Estado)
        VALUES (@TipoHabitacionId, @Numero, @Piso, @Estado);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS HabitacionId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Habitacion_Obtener @HabitacionId INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT h.*, t.Nombre AS TipoHabitacion
        FROM Habitacion h
        JOIN TipoHabitacion t ON t.TipoHabitacionId = h.TipoHabitacionId
        WHERE @HabitacionId IS NULL OR h.HabitacionId = @HabitacionId
        ORDER BY h.Numero;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Habitacion_Actualizar
    @HabitacionId INT, @TipoHabitacionId INT, @Numero VARCHAR(10), @Piso TINYINT, @Estado VARCHAR(15)
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE Habitacion
        SET TipoHabitacionId = @TipoHabitacionId, Numero = @Numero, Piso = @Piso, Estado = @Estado
        WHERE HabitacionId = @HabitacionId;
        IF @@ROWCOUNT = 0 THROW 50000, 'La habitación no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Habitacion_Eliminar @HabitacionId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM Habitacion WHERE HabitacionId = @HabitacionId;
        IF @@ROWCOUNT = 0 THROW 50000, 'La habitación no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--SERICIO
CREATE OR ALTER PROCEDURE sp_Servicio_Insertar @Nombre NVARCHAR(80), @Precio DECIMAL(12,2), @Activo BIT = 1
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO Servicio (Nombre, Precio, Activo) VALUES (@Nombre, @Precio, @Activo);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS ServicioId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Servicio_Obtener @ServicioId INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT * FROM Servicio WHERE @ServicioId IS NULL OR ServicioId = @ServicioId ORDER BY Nombre;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Servicio_Actualizar @ServicioId INT, @Nombre NVARCHAR(80), @Precio DECIMAL(12,2), @Activo BIT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE Servicio SET Nombre = @Nombre, Precio = @Precio, Activo = @Activo WHERE ServicioId = @ServicioId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El servicio no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Servicio_Eliminar @ServicioId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM Servicio WHERE ServicioId = @ServicioId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El servicio no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--USUARIO
CREATE OR ALTER PROCEDURE sp_Usuario_Insertar
    @PersonaId INT, @Rol VARCHAR(20), @ContrasenaHash VARCHAR(255), @Activo BIT = 1
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO Usuario (PersonaId, Rol, ContrasenaHash, Activo)
        VALUES (@PersonaId, @Rol, @ContrasenaHash, @Activo);
        COMMIT;
        SELECT SCOPE_IDENTITY() AS UsuarioId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Usuario_Obtener @UsuarioId INT = NULL, @Correo VARCHAR(100) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT u.UsuarioId, u.PersonaId, p.Nombre, p.Apellidos, p.Correo, u.Rol, u.ContrasenaHash, u.Activo
        FROM Usuario u
        JOIN Persona p ON p.PersonaId = u.PersonaId
        WHERE (@UsuarioId IS NULL OR u.UsuarioId = @UsuarioId)
          AND (@Correo IS NULL OR p.Correo = @Correo);
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Usuario_Actualizar @UsuarioId INT, @Rol VARCHAR(20), @ContrasenaHash VARCHAR(255), @Activo BIT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE Usuario SET Rol = @Rol, ContrasenaHash = @ContrasenaHash, Activo = @Activo WHERE UsuarioId = @UsuarioId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El usuario no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Usuario_Eliminar @UsuarioId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM Usuario WHERE UsuarioId = @UsuarioId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El usuario no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/* ---------- Cargo (el trigger tr_Cargo_AfterInsert valida y suma al total) ---------- */
CREATE OR ALTER PROCEDURE sp_Cargo_Insertar @ReservaId INT, @ServicioId INT, @Cantidad INT = 1
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        INSERT INTO Cargo (ReservaId, ServicioId, Cantidad, PrecioUnitario)
        SELECT @ReservaId, ServicioId, @Cantidad, Precio FROM Servicio WHERE ServicioId = @ServicioId AND Activo = 1;
        IF @@ROWCOUNT = 0 THROW 50000, 'El servicio no existe o no está activo.', 1;
        COMMIT;
        SELECT SCOPE_IDENTITY() AS CargoId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Cargo_Obtener @CargoId INT = NULL, @ReservaId INT = NULL
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT c.*, s.Nombre AS Servicio, c.Cantidad * c.PrecioUnitario AS Subtotal
        FROM Cargo c
        JOIN Servicio s ON s.ServicioId = c.ServicioId
        WHERE (@CargoId IS NULL OR c.CargoId = @CargoId) AND (@ReservaId IS NULL OR c.ReservaId = @ReservaId)
        ORDER BY c.Fecha;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Cargo_Actualizar @CargoId INT, @Cantidad INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        UPDATE Cargo SET Cantidad = @Cantidad WHERE CargoId = @CargoId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El cargo no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO
--
CREATE OR ALTER PROCEDURE sp_Cargo_Eliminar @CargoId INT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        BEGIN TRANSACTION;
        DELETE FROM Cargo WHERE CargoId = @CargoId;
        IF @@ROWCOUNT = 0 THROW 50000, 'El cargo no existe.', 1;
        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--5. PROCEDIMIENTOS NO BÁSICOS
/*sp_CrearReserva
Busca una habitación libre del tipo pedido, calcula el total con impuesto,
crea la reserva con su detalle y registra el adelanto si lo hay.
Utiliza UPDLOCK + HOLDLOCK bloquea las habitaciones del tipo hasta el
COMMIT. Si dos personas reservan a la vez, la segunda espera y después ya
ve ocupada la habitación. @EsperaDemo = 1 lo que hace es retener el bloqueo 10 segundos
para demostrarlo en la defensa.*/
CREATE OR ALTER PROCEDURE sp_CrearReserva
    @HuespedId INT,
    @TipoHabitacionId  INT,
    @FechaEntrada DATE,
    @FechaSalida DATE,
    @CantPersonas TINYINT,
    @Adelanto DECIMAL(12,2) = 0,
    @Metodo VARCHAR(15) = NULL,
    @EsperaDemo BIT = 0
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @HabitacionId INT, @Noches INT, @Precio DECIMAL(12,2), @Capacidad TINYINT,
            @Impuesto DECIMAL(5,2), @Subtotal DECIMAL(12,2), @Total DECIMAL(12,2), @ReservaId INT,
            @Bloqueadas INT;
    BEGIN TRY
        -- Validar fechas
        IF @FechaEntrada < CAST(GETDATE() AS DATE)
            THROW 50001, 'La fecha de entrada no puede ser anterior a hoy.', 1;
        IF @FechaSalida <= @FechaEntrada
            THROW 50001, 'La fecha de salida debe ser posterior a la de entrada.', 1;

        SELECT @Precio = PrecioNoche, @Capacidad = Capacidad
        FROM TipoHabitacion WHERE TipoHabitacionId = @TipoHabitacionId AND Activo = 1;
        IF @Precio IS NULL
            THROW 50002, 'El tipo de habitación no existe.', 1;
        IF @CantPersonas > @Capacidad
            THROW 50003, 'La cantidad de personas supera la capacidad de la habitación.', 1;
        IF @Adelanto > 0 AND @Metodo IS NULL
            THROW 50004, 'Indique el método de pago del adelanto.', 1;

        BEGIN TRANSACTION;

        -- Bloqueo de las habitaciones de ese tipo (control de concurrencia)
        SELECT @Bloqueadas = COUNT(*) FROM Habitacion WITH (UPDLOCK, HOLDLOCK)
        WHERE TipoHabitacionId = @TipoHabitacionId;

        IF @EsperaDemo = 1 WAITFOR DELAY '00:00:10';

        -- Primera habitación del tipo sin reservas activas que se crucen con las fechas
        SELECT TOP 1 @HabitacionId = h.HabitacionId
        FROM Habitacion h
        WHERE h.TipoHabitacionId = @TipoHabitacionId
          AND h.Estado <> 'MANTENIMIENTO'
          AND NOT EXISTS (SELECT 1
                          FROM DetalleReserva d
                          JOIN Reserva r ON r.ReservaId = d.ReservaId
                          WHERE d.HabitacionId = h.HabitacionId
                            AND r.Estado IN ('PENDIENTE', 'CONFIRMADA', 'EN_CURSO')
                            AND r.FechaEntrada < @FechaSalida
                            AND r.FechaSalida  > @FechaEntrada)
        ORDER BY h.Numero;

        IF @HabitacionId IS NULL
            THROW 50005, 'No hay habitaciones disponibles de ese tipo para esas fechas.', 1;

        -- Montos
        SELECT TOP 1 @Impuesto = PorcentajeImpuesto FROM Hotel;
        SET @Noches = DATEDIFF(DAY, @FechaEntrada, @FechaSalida);
        SET @Subtotal = @Noches * @Precio;
        SET @Total = ROUND(@Subtotal * (1 + @Impuesto / 100), 2);

        IF @Adelanto > @Total
            THROW 50004, 'El adelanto no puede ser mayor que el total.', 1;

        -- Inserciones
        INSERT INTO Reserva (HuespedId, FechaEntrada, FechaSalida, CantPersonas, Estado, Total)
        VALUES (@HuespedId, @FechaEntrada, @FechaSalida, @CantPersonas,
                CASE WHEN @Adelanto > 0 THEN 'CONFIRMADA' ELSE 'PENDIENTE' END, @Total);
        SET @ReservaId = SCOPE_IDENTITY();

        INSERT INTO DetalleReserva (ReservaId, HabitacionId, Noches, PrecioNoche, Subtotal)
        VALUES (@ReservaId, @HabitacionId, @Noches, @Precio, @Subtotal);

        IF @Adelanto > 0
            INSERT INTO Pago (ReservaId, Tipo, Metodo, Monto) VALUES (@ReservaId, 'ADELANTO', @Metodo, @Adelanto);

        COMMIT;

        SELECT @ReservaId AS ReservaId, h.Numero AS Habitacion, @Noches AS Noches, @Total AS Total
        FROM Habitacion h WHERE h.HabitacionId = @HabitacionId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/* sp_CheckOut
   Calcula hospedaje + consumos + impuesto, registra el pago final, verifica
   que lo pagado cubra el total, finaliza la reserva y deja la habitación como SUCIA.
   Si falta dinero se revierte todo, incluido el pago. */
CREATE OR ALTER PROCEDURE sp_CheckOut
    @ReservaId  INT,
    @Monto DECIMAL(12,2) = 0,
    @Metodo VARCHAR(15) = NULL
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @Estado VARCHAR(12), @Hospedaje DECIMAL(12,2), @Consumos DECIMAL(12,2),
            @Impuesto DECIMAL(5,2), @Total DECIMAL(12,2), @Pagado DECIMAL(12,2);
    BEGIN TRY
        BEGIN TRANSACTION;

        SELECT @Estado = Estado FROM Reserva WITH (UPDLOCK) WHERE ReservaId = @ReservaId;
        IF @Estado IS NULL
            THROW 50006, 'La reserva no existe.', 1;
        IF @Estado <> 'EN_CURSO'
            THROW 50007, 'Solo se puede hacer check-out de una reserva EN_CURSO.', 1;

        -- Total final
        SELECT @Hospedaje = SUM(Subtotal) FROM DetalleReserva WHERE ReservaId = @ReservaId;
        SELECT @Consumos = ISNULL(SUM(Cantidad * PrecioUnitario), 0) FROM Cargo WHERE ReservaId = @ReservaId;
        SELECT TOP 1 @Impuesto = PorcentajeImpuesto FROM Hotel;
        SET @Total = ROUND((@Hospedaje + @Consumos) * (1 + @Impuesto / 100), 2);

        -- Pago final
        IF @Monto > 0
        BEGIN
            IF @Metodo IS NULL THROW 50004, 'Indique el método de pago.', 1;
            INSERT INTO Pago (ReservaId, Tipo, Metodo, Monto) VALUES (@ReservaId, 'PAGO', @Metodo, @Monto);
        END

        SELECT @Pagado = ISNULL(SUM(CASE WHEN Tipo = 'REEMBOLSO' THEN -Monto ELSE Monto END), 0)
        FROM Pago WHERE ReservaId = @ReservaId;

        IF @Pagado < @Total
        BEGIN
            DECLARE @Mensaje NVARCHAR(200) = CONCAT('Saldo pendiente: ', @Total - @Pagado);
            THROW 50008, @Mensaje, 1;
        END

        -- Cierre
        UPDATE Reserva SET Estado = 'FINALIZADA', Total = @Total WHERE ReservaId = @ReservaId;

        UPDATE h SET Estado = 'SUCIA'
        FROM Habitacion h
        JOIN DetalleReserva d ON d.HabitacionId = h.HabitacionId
        WHERE d.ReservaId = @ReservaId;

        COMMIT;

        SELECT @ReservaId AS ReservaId, @Hospedaje AS Hospedaje, @Consumos AS Consumos,
               @Total AS Total, @Pagado AS Pagado, @Pagado - @Total AS Vuelto;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/*sp_CancelarReserva
Si se cancela con menos de 3 días de anticipación, el hotel retiene el 50 %
de lo pagado; si no, devuelve todo. Registra el reembolso y cancela. */
CREATE OR ALTER PROCEDURE sp_CancelarReserva
    @ReservaId INT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @Estado VARCHAR(12), @Entrada DATE, @Dias INT, @Pagado DECIMAL(12,2),
            @Reembolso DECIMAL(12,2), @Metodo VARCHAR(15);
    BEGIN TRY
        BEGIN TRANSACTION;

        SELECT @Estado = Estado, @Entrada = FechaEntrada
        FROM Reserva WITH (UPDLOCK) WHERE ReservaId = @ReservaId;

        IF @Estado IS NULL
            THROW 50006, 'La reserva no existe.', 1;
        IF @Estado NOT IN ('PENDIENTE', 'CONFIRMADA')
            THROW 50007, 'Solo se pueden cancelar reservas PENDIENTES o CONFIRMADAS.', 1;

        SET @Dias = DATEDIFF(DAY, CAST(GETDATE() AS DATE), @Entrada);

        SELECT @Pagado = ISNULL(SUM(CASE WHEN Tipo = 'REEMBOLSO' THEN -Monto ELSE Monto END), 0)
        FROM Pago WHERE ReservaId = @ReservaId;

        -- el cliente tiene tres dias de anticipacion para cancelar y recibir reembolso
        IF @Dias < 3
            SET @Reembolso = ROUND(@Pagado * 0.5, 2);
        ELSE
            SET @Reembolso = @Pagado;

        IF @Reembolso > 0
        BEGIN
            SELECT TOP 1 @Metodo = Metodo FROM Pago WHERE ReservaId = @ReservaId ORDER BY Fecha;
            INSERT INTO Pago (ReservaId, Tipo, Metodo, Monto) VALUES (@ReservaId, 'REEMBOLSO', @Metodo, @Reembolso);
        END

        UPDATE Reserva SET Estado = 'CANCELADA' WHERE ReservaId = @ReservaId;

        COMMIT;

        SELECT @ReservaId AS ReservaId, @Dias AS DiasAnticipacion, @Pagado AS Pagado,
               @Reembolso AS Reembolso, @Pagado - @Reembolso AS Retenido;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--6. PROCEDIMIENTOS DE APOYO

/* Registra una persona (la cedula y telefono se encriptan) como HUESPED o
   EMPLEADO, y su usuario si se envía la contraseña.
   WITH EXECUTE AS OWNER puede abrir la llave sin dar permisos a quien lo llama. */
CREATE OR ALTER PROCEDURE sp_RegistrarPersona
    @Tipo VARCHAR(10),  -- HUESPED o EMPLEADO
    @Cedula NVARCHAR(20),
    @Nombre NVARCHAR(60),
    @Apellidos NVARCHAR(100),
    @Correo VARCHAR(100),
    @Telefono NVARCHAR(20) = NULL,
    @Nacionalidad NVARCHAR(30)  = 'Costa Rica',
    @Puesto VARCHAR(20) = NULL,-- solo empleados
    @Salario DECIMAL(12,2) = NULL, -- solo empleados
    @ContrasenaHash VARCHAR(255)  = NULL
WITH EXECUTE AS OWNER
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @PersonaId INT;
    BEGIN TRY
        IF @Tipo NOT IN ('HUESPED', 'EMPLEADO')
            THROW 50009, 'El tipo debe ser HUESPED o EMPLEADO.', 1;

        BEGIN TRANSACTION;

        OPEN SYMMETRIC KEY LlaveHotel DECRYPTION BY CERTIFICATE CertHotel;

        INSERT INTO Persona (Cedula, Nombre, Apellidos, Correo, Telefono)
        VALUES (ENCRYPTBYKEY(KEY_GUID('LlaveHotel'), @Cedula), @Nombre, @Apellidos, @Correo,
                ENCRYPTBYKEY(KEY_GUID('LlaveHotel'), @Telefono));
        SET @PersonaId = SCOPE_IDENTITY();

        CLOSE SYMMETRIC KEY LlaveHotel;

        IF @Tipo = 'HUESPED'
            INSERT INTO Huesped (PersonaId, Nacionalidad) VALUES (@PersonaId, @Nacionalidad);
        ELSE
            INSERT INTO Empleado (PersonaId, Puesto, Salario) VALUES (@PersonaId, @Puesto, @Salario);

        IF @ContrasenaHash IS NOT NULL
            INSERT INTO Usuario (PersonaId, Rol, ContrasenaHash)
            VALUES (@PersonaId, CASE WHEN @Tipo = 'HUESPED' THEN 'HUESPED' ELSE @Puesto END, @ContrasenaHash);

        COMMIT;
        SELECT @PersonaId AS PersonaId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/* Datos de una persona con la cedula y el telefono desencriptados */
CREATE OR ALTER PROCEDURE sp_ObtenerPersona @PersonaId INT
WITH EXECUTE AS OWNER
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        OPEN SYMMETRIC KEY LlaveHotel DECRYPTION BY CERTIFICATE CertHotel;

        SELECT p.PersonaId, p.Nombre, p.Apellidos, p.Correo,
               CONVERT(NVARCHAR(20), DECRYPTBYKEY(p.Cedula))   AS Cedula,
               CONVERT(NVARCHAR(20), DECRYPTBYKEY(p.Telefono)) AS Telefono,
               h.Nacionalidad, e.Puesto
        FROM Persona p
        LEFT JOIN Huesped h  ON h.PersonaId = p.PersonaId
        LEFT JOIN Empleado e ON e.PersonaId = p.PersonaId
        WHERE p.PersonaId = @PersonaId;

        CLOSE SYMMETRIC KEY LlaveHotel;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO

--Check-in: la reserva pasa a EN_CURSO y la habitación a OCUPADA
CREATE OR ALTER PROCEDURE sp_CheckIn @ReservaId INT
AS
BEGIN
    SET NOCOUNT ON;
    DECLARE @Estado VARCHAR(12), @Entrada DATE;
    BEGIN TRY
        BEGIN TRANSACTION;

        SELECT @Estado = Estado, @Entrada = FechaEntrada FROM Reserva WITH (UPDLOCK) WHERE ReservaId = @ReservaId;
        IF @Estado IS NULL
            THROW 50006, 'La reserva no existe.', 1;
        IF @Estado NOT IN ('PENDIENTE', 'CONFIRMADA')
            THROW 50007, 'La reserva no está pendiente ni confirmada.', 1;
        IF @Entrada > CAST(GETDATE() AS DATE)
            THROW 50001, 'Todavía no es la fecha de entrada.', 1;

        UPDATE Reserva SET Estado = 'EN_CURSO' WHERE ReservaId = @ReservaId;

        UPDATE h SET Estado = 'OCUPADA'
        FROM Habitacion h
        JOIN DetalleReserva d ON d.HabitacionId = h.HabitacionId
        WHERE d.ReservaId = @ReservaId;

        COMMIT;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

--7. PROCEDIMIENTO DE REPORTE
--Ingresos por mes y tipo de habitación en un rango de fechas.
CREATE OR ALTER PROCEDURE sp_ReporteIngresos
    @Desde DATE,
    @Hasta DATE
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        SELECT FORMAT(r.FechaEntrada, 'yyyy-MM') AS Mes,
               t.Nombre AS TipoHabitacion,
               COUNT(DISTINCT r.ReservaId)AS Reservas,
               SUM(d.Noches) AS Noches,
               SUM(d.Subtotal) AS IngresoHospedaje,
               SUM(ISNULL(c.Consumos, 0)) AS IngresoConsumos
        FROM Reserva r
        JOIN DetalleReserva d ON d.ReservaId = r.ReservaId
        JOIN Habitacion h ON h.HabitacionId = d.HabitacionId
        JOIN TipoHabitacion t ON t.TipoHabitacionId = h.TipoHabitacionId
        LEFT JOIN (SELECT ReservaId, SUM(Cantidad * PrecioUnitario) AS Consumos
                   FROM Cargo GROUP BY ReservaId) c ON c.ReservaId = r.ReservaId
        WHERE r.Estado = 'FINALIZADA'
          AND r.FechaEntrada BETWEEN @Desde AND @Hasta
        GROUP BY FORMAT(r.FechaEntrada, 'yyyy-MM'), t.Nombre
        ORDER BY Mes, TipoHabitacion;
    END TRY
    BEGIN CATCH
        THROW;
    END CATCH
END
GO

 -- 8. TRIGGERS

--INSTEAD OF DELETE: una reserva con pagos no se borra, se cancela.
--Si no tiene pagos, se borra junto con su detalle.

CREATE OR ALTER TRIGGER tr_Reserva_InsteadOfDelete
ON Reserva
INSTEAD OF DELETE
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF EXISTS (SELECT 1 FROM deleted WHERE Estado IN ('EN_CURSO', 'FINALIZADA'))
            THROW 50010, 'No se puede eliminar una reserva EN_CURSO o FINALIZADA.', 1;

        -- Con pagos: se cancelan
        UPDATE r SET Estado = 'CANCELADA'
        FROM Reserva r
        JOIN deleted d ON d.ReservaId = r.ReservaId
        WHERE EXISTS (SELECT 1 FROM Pago p WHERE p.ReservaId = d.ReservaId);

        -- Sin pagos se borra completo
        DELETE dr
        FROM DetalleReserva dr
        JOIN deleted d ON d.ReservaId = dr.ReservaId
        WHERE NOT EXISTS (SELECT 1 FROM Pago p WHERE p.ReservaId = d.ReservaId);

        DELETE r
        FROM Reserva r
        JOIN deleted d ON d.ReservaId = r.ReservaId
        WHERE NOT EXISTS (SELECT 1 FROM Pago p WHERE p.ReservaId = d.ReservaId);
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/* AFTER INSERT, UPDATE, DELETE: auditoría de pagos.
   Además, un pago registrado no puede cambiar de monto. */
CREATE OR ALTER TRIGGER tr_Pago_Auditoria
ON Pago
AFTER INSERT, UPDATE, DELETE
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF EXISTS (SELECT 1 FROM inserted i JOIN deleted d ON d.PagoId = i.PagoId WHERE i.Monto <> d.Monto)
            THROW 50011, 'El monto de un pago no se puede modificar. Registre un reembolso.', 1;

        -- Nuevos
        INSERT INTO Auditoria (Tabla, Operacion, Datos)
        SELECT 'Pago', 'I', (SELECT x.* FROM inserted x WHERE x.PagoId = i.PagoId FOR JSON PATH, WITHOUT_ARRAY_WRAPPER)
        FROM inserted i
        WHERE NOT EXISTS (SELECT 1 FROM deleted d WHERE d.PagoId = i.PagoId);

        -- Modificados
        INSERT INTO Auditoria (Tabla, Operacion, Datos)
        SELECT 'Pago', 'U', (SELECT x.* FROM inserted x WHERE x.PagoId = i.PagoId FOR JSON PATH, WITHOUT_ARRAY_WRAPPER)
        FROM inserted i
        WHERE EXISTS (SELECT 1 FROM deleted d WHERE d.PagoId = i.PagoId);

        -- Borrados
        INSERT INTO Auditoria (Tabla, Operacion, Datos)
        SELECT 'Pago', 'D', (SELECT x.* FROM deleted x WHERE x.PagoId = d.PagoId FOR JSON PATH, WITHOUT_ARRAY_WRAPPER)
        FROM deleted d
        WHERE NOT EXISTS (SELECT 1 FROM inserted i WHERE i.PagoId = d.PagoId);
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/* AFTER INSERT: solo se cargan consumos a reservas EN_CURSO, y cada consumo
   (con impuesto) se suma al total de la reserva. */
CREATE OR ALTER TRIGGER tr_Cargo_AfterInsert
ON Cargo
AFTER INSERT
AS
BEGIN
    SET NOCOUNT ON;
    BEGIN TRY
        IF EXISTS (SELECT 1 FROM inserted i JOIN Reserva r ON r.ReservaId = i.ReservaId WHERE r.Estado <> 'EN_CURSO')
            THROW 50012, 'Solo se pueden cargar consumos a una reserva EN_CURSO.', 1;

        UPDATE r
        SET Total = r.Total + ROUND(x.Consumo * (1 + (SELECT TOP 1 PorcentajeImpuesto FROM Hotel) / 100), 2)
        FROM Reserva r
        JOIN (SELECT ReservaId, SUM(Cantidad * PrecioUnitario) AS Consumo
              FROM inserted GROUP BY ReservaId) x ON x.ReservaId = r.ReservaId;
    END TRY
    BEGIN CATCH
        IF @@TRANCOUNT > 0 ROLLBACK;
        THROW;
    END CATCH
END
GO

/*
   9. SEGURIDAD
   Login            Rol de base de datos   Permisos
   app_hotel        rol_aplicacion         leer y escribir tablas + ejecutar procedimientos (Laravel)
   admin_hotel      rol_administrador      todo lo anterior + ver auditoría
   recep_hotel      rol_recepcion          solo los procedimientos de reservas
   Nadie puede modificar ni borrar la auditoría. Ninguno tiene permiso sobre
   la llave de encriptación: solo la usan sp_RegistrarPersona y sp_ObtenerPersona.
*/
USE master;
GO
IF NOT EXISTS (SELECT 1 FROM sys.server_principals WHERE name = 'app_hotel')
    CREATE LOGIN app_hotel   WITH PASSWORD = 'App#Hotel2026',   DEFAULT_DATABASE = HotelDB;
IF NOT EXISTS (SELECT 1 FROM sys.server_principals WHERE name = 'admin_hotel')
    CREATE LOGIN admin_hotel WITH PASSWORD = 'Admin#Hotel2026', DEFAULT_DATABASE = HotelDB;
IF NOT EXISTS (SELECT 1 FROM sys.server_principals WHERE name = 'recep_hotel')
    CREATE LOGIN recep_hotel WITH PASSWORD = 'Recep#Hotel2026', DEFAULT_DATABASE = HotelDB;
GO
USE HotelDB;
GO
CREATE USER app_hotel FOR LOGIN app_hotel;
CREATE USER admin_hotel FOR LOGIN admin_hotel;
CREATE USER recep_hotel FOR LOGIN recep_hotel;

CREATE ROLE rol_aplicacion;
CREATE ROLE rol_administrador;
CREATE ROLE rol_recepcion;

ALTER ROLE rol_aplicacion ADD MEMBER app_hotel;
ALTER ROLE rol_administrador ADD MEMBER admin_hotel;
ALTER ROLE rol_recepcion ADD MEMBER recep_hotel;

-- Laravel
GRANT SELECT, INSERT, UPDATE, DELETE ON SCHEMA::dbo TO rol_aplicacion;
GRANT EXECUTE ON SCHEMA::dbo TO rol_aplicacion;

-- Administrador
GRANT SELECT, INSERT, UPDATE, DELETE ON SCHEMA::dbo TO rol_administrador;
GRANT EXECUTE ON SCHEMA::dbo TO rol_administrador;

-- Recepcion: solo procedimientos de reservas
GRANT EXECUTE ON sp_CrearReserva TO rol_recepcion;
GRANT EXECUTE ON sp_CheckIn TO rol_recepcion;
GRANT EXECUTE ON sp_CheckOut TO rol_recepcion;
GRANT EXECUTE ON sp_CancelarReserva TO rol_recepcion;
GRANT EXECUTE ON sp_Cargo_Insertar TO rol_recepcion;
GRANT EXECUTE ON sp_ReporteIngresos TO rol_recepcion;

-- La auditoria solo lee
DENY UPDATE, DELETE ON Auditoria TO rol_aplicacion, rol_administrador, rol_recepcion;
DENY SELECT ON Auditoria TO rol_aplicacion, rol_recepcion;
GO

/*10. DATOS DE PRUEBA
Contraseña de todos los usuarios del sitio: Hotel2026*
 */
INSERT INTO Hotel (Nombre, Descripcion, LogoUrl, Direccion, Latitud, Longitud, Telefono, Correo)
VALUES ('Hotel Arrecife Azul', 'Hotel de 6 habitaciones a 100 metros de playa Cocles.', '/storage/logo.png',
        'Playa Cocles, Puerto Viejo, Limón', 9.646700, -82.738700, '2750-0000', 'reservas@arrecifeazul.test');

INSERT INTO ImagenSitio (HotelId, Tipo, Titulo, ImagenUrl, Orden) VALUES
 (1, 'SLIDE',   'Su refugio en el Caribe', '/storage/slides/playa.jpg', 1),
 (1, 'SLIDE',   'Suites frente al mar',    '/storage/slides/suite.jpg', 2),
 (1, 'GALERIA', 'Piscina',                 '/storage/galeria/piscina.jpg', 1),
 (1, 'GALERIA', 'Desayuno caribeño',       '/storage/galeria/desayuno.jpg', 2);

INSERT INTO TipoHabitacion (Nombre, Descripcion, Capacidad, PrecioNoche, FotoUrl) VALUES
 ('Estándar', 'Habitación con vista al jardín.',       2, 45000, '/storage/habitaciones/estandar.jpg'),
 ('Superior', 'Más espacio y terraza privada.',        3, 62000, '/storage/habitaciones/superior.jpg'),
 ('Suite',    'Balcón frente al mar y sala de estar.', 2, 98000, '/storage/habitaciones/suite.jpg');

INSERT INTO Habitacion (TipoHabitacionId, Numero, Piso) VALUES
 (1, '101', 1), (1, '102', 1), (2, '201', 2), (2, '202', 2), (3, '301', 3), (3, '302', 3);

INSERT INTO Servicio (Nombre, Precio) VALUES
 ('Desayuno', 7500), ('Cena', 12000), ('Lavandería', 6000), ('Tour a Cahuita', 25000);

-- Personas: 1 y 2 empleados, 3 a 6 huéspedes
DECLARE @Hash VARCHAR(255) = '$2y$12$Ir083wL6M2PGFDHYaXJMD.YZWRIb56iUb4gFZTJj1.wrUmaEYxMMK';
EXEC sp_RegistrarPersona 'EMPLEADO', '1-1111-1111', 'Laura', 'Mora Vargas', 'admin@arrecifeazul.test',
     '8811-0001', NULL, 'ADMINISTRADOR', 1200000, @Hash;
EXEC sp_RegistrarPersona 'EMPLEADO', '7-0222-0222', 'Carlos', 'Jiménez Solano', 'recepcion@arrecifeazul.test',
     '8811-0002', NULL, 'RECEPCIONISTA', 650000, @Hash;
EXEC sp_RegistrarPersona 'HUESPED', '1-0501-0101', 'María', 'Rodríguez Castro', 'maria@correo.test', '8700-0101', 'Costa Rica', NULL, NULL, @Hash;
EXEC sp_RegistrarPersona 'HUESPED', '2-0602-0202', 'José', 'Vargas Alfaro', 'jose@correo.test', '8700-0102', 'Costa Rica', NULL, NULL, @Hash;
EXEC sp_RegistrarPersona 'HUESPED', 'X1234567', 'Emily', 'Johnson', 'emily@correo.test', '+1 555 0103', 'Estados Unidos', NULL, NULL, @Hash;
EXEC sp_RegistrarPersona 'HUESPED', '3-0703-0303', 'Daniela', 'Solís Méndez', 'daniela@correo.test', '8700-0104', 'Costa Rica', NULL, NULL, @Hash;
GO

-- Historial: 5 reservas FINALIZADAS (para el reporte y las reseñas de MongoDB)
DECLARE @Hoy DATE = CAST(GETDATE() AS DATE);
INSERT INTO Reserva (HuespedId, FechaEntrada, FechaSalida, CantPersonas, Estado, Total) VALUES
 (3, DATEADD(DAY, -50, @Hoy), DATEADD(DAY, -47, @Hoy), 2, 'FINALIZADA', 152550),   -- 1: 101, 3 noches
 (4, DATEADD(DAY, -40, @Hoy), DATEADD(DAY, -38, @Hoy), 3, 'FINALIZADA', 140120),   -- 2: 201, 2 noches
 (5, DATEADD(DAY, -30, @Hoy), DATEADD(DAY, -26, @Hoy), 2, 'FINALIZADA', 442960),   -- 3: 301, 4 noches
 (6, DATEADD(DAY, -20, @Hoy), DATEADD(DAY, -19, @Hoy), 1, 'FINALIZADA', 50850),    -- 4: 102, 1 noche
 (3, DATEADD(DAY, -10, @Hoy), DATEADD(DAY,  -7, @Hoy), 2, 'FINALIZADA', 332220);   -- 5: 302, 3 noches

INSERT INTO DetalleReserva (ReservaId, HabitacionId, Noches, PrecioNoche, Subtotal) VALUES
 (1, 1, 3, 45000, 135000), (2, 3, 2, 62000, 124000), (3, 5, 4, 98000, 392000),
 (4, 2, 1, 45000,  45000), (5, 6, 3, 98000, 294000);

INSERT INTO Pago (ReservaId, Tipo, Metodo, Monto) VALUES
 (1, 'PAGO', 'TARJETA', 152550), (2, 'PAGO', 'SINPE', 140120), (3, 'PAGO', 'TARJETA', 442960),
 (4, 'PAGO', 'EFECTIVO', 50850), (5, 'PAGO', 'SINPE', 332220);

-- Reservas actuales y futuras, creadas con el procedimiento
DECLARE @E DATE, @S DATE;
SELECT @E = @Hoy, @S = DATEADD(DAY, 2, @Hoy);
EXEC sp_CrearReserva 6, 3, @E, @S, 2, 50000, 'SINPE';     -- 6: Suite 301, llega hoy
EXEC sp_CheckIn 6;
EXEC sp_Cargo_Insertar 6, 1, 2;                           -- 2 desayunos
SELECT @E = DATEADD(DAY, 5, @Hoy), @S = DATEADD(DAY, 8, @Hoy);
EXEC sp_CrearReserva 4, 2, @E, @S, 2;                     -- 7: Superior, PENDIENTE
GO

/* =====================================================================
   11. PRUEBAS PARA LA DEFENSA
   Todo este bloque está en comentario. Para probar, seleccione solo las
   líneas de una prueba y ejecútelas (F5 ejecuta únicamente lo seleccionado).
   Háganlas en este orden, justo después de crear la base.

-- a) Crear una reserva y ver un error controlado
DECLARE @E DATE = DATEADD(DAY, 10, CAST(GETDATE() AS DATE)), @S DATE = DATEADD(DAY, 12, CAST(GETDATE() AS DATE));
EXEC sp_CrearReserva 5, 1, @E, @S, 2, 20000, 'TARJETA';   -- correcta: será la reserva 8
EXEC sp_CrearReserva 5, 1, @E, @S, 5;                      -- error: supera la capacidad

-- b) Consumo a una reserva que no está en curso (trigger tr_Cargo_AfterInsert)
EXEC sp_Cargo_Insertar 7, 1, 1;

-- c) CONCURRENCIA (antes del check-out de la prueba d).
--    Mañana solo queda libre la Suite 302, porque la 301 está ocupada por la reserva 6.
--    Abra dos ventanas de consulta en SSMS.
--    Ventana 1 (retiene el bloqueo 10 segundos):
DECLARE @E DATE = DATEADD(DAY, 1, CAST(GETDATE() AS DATE)), @S DATE = DATEADD(DAY, 2, CAST(GETDATE() AS DATE));
EXEC sp_CrearReserva 3, 3, @E, @S, 2, 0, NULL, 1;
--    Ventana 2 (ejecútela enseguida): espera a la ventana 1 y luego dice que no hay disponibilidad
DECLARE @E DATE = DATEADD(DAY, 1, CAST(GETDATE() AS DATE)), @S DATE = DATEADD(DAY, 2, CAST(GETDATE() AS DATE));
EXEC sp_CrearReserva 4, 3, @E, @S, 2;

-- d) Check-out: primero sin pagar (error), luego pagando el saldo exacto
EXEC sp_CheckOut 6;
EXEC sp_CheckOut 6, 188430, 'TARJETA';

-- e) Cancelar una reserva
EXEC sp_CancelarReserva 7;

-- f) INSTEAD OF DELETE: la reserva 8 (prueba a) tiene un adelanto, así que queda CANCELADA
DELETE FROM Reserva WHERE ReservaId = 8;
SELECT ReservaId, Estado FROM Reserva;

-- g) Auditoría de pagos y regla del monto
UPDATE Pago SET Monto = 1 WHERE PagoId = 1;               -- error
SELECT * FROM Auditoria;

-- h) Reporte de los últimos 3 meses
DECLARE @D DATE = DATEADD(MONTH, -3, GETDATE()), @H DATE = GETDATE();
EXEC sp_ReporteIngresos @D, @H;

-- i) Encriptación: la tabla muestra bytes, el procedimiento los descifra
SELECT PersonaId, Nombre, Cedula, Telefono FROM Persona;
EXEC sp_ObtenerPersona 3;

-- j) Seguridad: recepción no puede leer tablas, pero sí usar sus procedimientos
EXECUTE AS USER = 'recep_hotel';
SELECT * FROM Persona;                                     -- error de permisos
EXEC sp_ReporteIngresos '2026-01-01', '2026-12-31';        -- permitido
REVERT;
   ===================================================================== */
