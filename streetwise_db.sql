-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-08-2026 a las 17:41:37
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `streetwise_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id log',
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK opcional -> users.user_id',
  `event_type` varchar(100) NOT NULL COMMENT 'Tipo de evento: login, update_product, delete_order, etc.',
  `description` text DEFAULT NULL COMMENT 'Descripción del evento',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP (IPv4/IPv6)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Registro de logs para auditoría y trazabilidad';

--
-- Volcado de datos para la tabla `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `event_type`, `description`, `ip_address`, `created_at`) VALUES
(1, 3, 'create_order', 'Pedido creado id=1', '190.12.34.56', '2026-06-20 20:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `carts`
--

CREATE TABLE `carts` (
  `cart_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id carrito',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> users.user_id (carrito pertenece a 1 usuario)',
  `created_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Creación del carrito',
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Última actualización'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Carritos de compra (un carrito activo por usuario)';

--
-- Volcado de datos para la tabla `carts`
--

INSERT INTO `carts` (`cart_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 3, '2026-06-20 20:33:19', '2026-06-20 20:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart_items`
--

CREATE TABLE `cart_items` (
  `cart_item_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id item en carrito',
  `cart_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> carts.cart_id (muchos items por carrito)',
  `variant_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> product_variants.variant_id (la variante seleccionada)',
  `quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Cantidad deseada',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Precio unitario en el momento de agregar',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Líneas (items) del carrito';

--
-- Volcado de datos para la tabla `cart_items`
--

INSERT INTO `cart_items` (`cart_item_id`, `cart_id`, `variant_id`, `quantity`, `unit_price`, `created_at`) VALUES
(1, 1, 2, 2, 29.90, '2026-06-20 20:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `categories`
--

CREATE TABLE `categories` (
  `category_id` smallint(5) UNSIGNED NOT NULL COMMENT 'PK: id categoría',
  `name` varchar(100) NOT NULL COMMENT 'Nombre de la categoría',
  `slug` varchar(120) NOT NULL COMMENT 'Slug para URLs (índice)',
  `description` text DEFAULT NULL COMMENT 'Descripción de la categoría',
  `parent_id` smallint(5) UNSIGNED DEFAULT NULL COMMENT 'Autorreferencia para subcategorías (FK a categories)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Categorías y subcategorías de productos';

--
-- Volcado de datos para la tabla `categories`
--

INSERT INTO `categories` (`category_id`, `name`, `slug`, `description`, `parent_id`) VALUES
(1, 'Hombres', 'hombres', 'Ropa para hombres', NULL),
(2, 'Mujeres', 'mujeres', 'Ropa para mujeres', NULL),
(3, 'Accesorios Hombre', 'accesorios-hombre', 'Accesorios para hombre', 1),
(4, 'Camisas Hombre', 'camisas-hombre', 'Camisas para hombre', 1),
(5, 'Pantalones Hombre', 'pantalones-hombre', 'Pantalones para hombre', 1),
(6, 'Chaquetas Hombre', 'chaquetas-hombre', 'Chaquetas para hombre', 1),
(7, 'Busos Hombre', 'busos-hombre', 'Busos para hombre', 1),
(8, 'Sudaderas Hombre', 'sudaderas-hombre', 'Sudaderas para hombre', 1),
(9, 'Accesorios Mujer', 'accesorios-mujer', 'Accesorios para mujer', 2),
(10, 'Camisas Mujer', 'camisas-mujer', 'Camisas para mujer', 2),
(11, 'Pantalones Mujer', 'pantalones-mujer', 'Pantalones para mujer', 2),
(12, 'Chaquetas Mujer', 'chaquetas-mujer', 'Chaquetas para mujer', 2),
(13, 'Busos Mujer', 'busos-mujer', 'Busos para mujer', 2),
(14, 'Sudaderas Mujer', 'sudaderas-mujer', 'Sudaderas para mujer', 2),
(15, 'Camisetas', 'camisetas', 'Camisetas y polos', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id mensaje',
  `user_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK opcional -> users.user_id (si está registrado)',
  `name` varchar(120) NOT NULL COMMENT 'Nombre remitente',
  `email` varchar(150) NOT NULL COMMENT 'Email remitente',
  `subject` varchar(200) NOT NULL COMMENT 'Asunto del mensaje',
  `message` text NOT NULL COMMENT 'Contenido del mensaje',
  `status` varchar(30) NOT NULL DEFAULT 'pendiente' COMMENT 'Estado: pendiente, en_proceso, resuelto',
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Mensajes de contacto y soporte';

--
-- Volcado de datos para la tabla `contact_messages`
--

INSERT INTO `contact_messages` (`message_id`, `user_id`, `name`, `email`, `subject`, `message`, `status`, `created_at`) VALUES
(1, 3, 'Mateo Ramos', 'mateo.ramos@example.com', 'Consulta sobre talla', '¿La talla M corresponde a 38-40?', 'pendiente', '2026-06-20 20:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id cupón',
  `code` varchar(50) NOT NULL COMMENT 'Código que usuario aplica',
  `description` varchar(255) DEFAULT NULL COMMENT 'Descripción del cupón',
  `discount_percent` decimal(5,2) DEFAULT NULL COMMENT 'Descuento en porcentaje (0-100)',
  `discount_amount` decimal(10,2) DEFAULT NULL COMMENT 'Descuento en valor fijo',
  `valid_from` datetime DEFAULT NULL COMMENT 'Fecha inicio vigencia',
  `valid_until` datetime DEFAULT NULL COMMENT 'Fecha fin vigencia',
  `active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Cupones y promociones';

--
-- Volcado de datos para la tabla `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `code`, `description`, `discount_percent`, `discount_amount`, `valid_from`, `valid_until`, `active`) VALUES
(1, 'SUMMER10', '10% descuento temporada verano', 10.00, NULL, '2026-06-20 20:33:19', '2026-07-20 20:33:19', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `invoices`
--

CREATE TABLE `invoices` (
  `invoice_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id factura',
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> orders.order_id (1:1 relación lógica con order)',
  `invoice_number` varchar(60) NOT NULL COMMENT 'Número de factura único',
  `issued_at` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha emisión',
  `amount` decimal(12,2) NOT NULL COMMENT 'Valor facturado'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Facturas generadas para pedidos';

--
-- Volcado de datos para la tabla `invoices`
--

INSERT INTO `invoices` (`invoice_id`, `order_id`, `invoice_number`, `issued_at`, `amount`) VALUES
(1, 1, 'INV-00000001', '2026-06-20 20:33:19', 65.18);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id del pedido',
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> users.user_id (pedido hecho por usuario)',
  `status` varchar(30) NOT NULL DEFAULT 'pendiente' COMMENT 'Estado del pedido: pendiente, confirmado, enviado, entregado, cancelado',
  `coupon_id` int(10) UNSIGNED DEFAULT NULL COMMENT 'FK opcional a coupons',
  `subtotal` decimal(12,2) NOT NULL COMMENT 'Subtotal antes de impuestos y descuentos',
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Impuestos aplicados',
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00 COMMENT 'Valor total descontado',
  `total` decimal(12,2) NOT NULL COMMENT 'Total a pagar',
  `payment_method` varchar(50) NOT NULL DEFAULT 'efectivo' COMMENT 'Método de pago: nequi, tarjeta, efectivo',
  `shipping_address` varchar(500) NOT NULL COMMENT 'Dirección de envío completa',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `status`, `coupon_id`, `subtotal`, `tax`, `discount`, `total`, `payment_method`, `shipping_address`, `created_at`, `updated_at`) VALUES
(1, 3, 'confirmado', 1, 59.80, 11.36, 5.98, 65.18, 'efectivo', 'Calle Falsa 123, Bogotá, Colombia', '2026-06-20 20:33:19', '2026-06-20 20:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id línea pedido',
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> orders.order_id (muchas líneas por pedido)',
  `variant_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> product_variants.variant_id',
  `quantity` smallint(5) UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Cantidad comprada',
  `unit_price` decimal(10,2) NOT NULL COMMENT 'Precio unitario al momento de la compra'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Líneas de los pedidos (historial)';

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `variant_id`, `quantity`, `unit_price`) VALUES
(1, 1, 2, 2, 29.90);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `product_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id producto',
  `category_id` smallint(5) UNSIGNED NOT NULL COMMENT 'FK -> categories.category_id',
  `sku` varchar(60) NOT NULL COMMENT 'SKU único del producto',
  `name` varchar(150) NOT NULL COMMENT 'Nombre del producto',
  `description` text DEFAULT NULL COMMENT 'Descripción detallada',
  `price` decimal(10,2) NOT NULL COMMENT 'Precio actual (sin aplicar descuentos)',
  `main_image` varchar(255) DEFAULT NULL COMMENT 'Ruta de la imagen principal para el catálogo',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Cantidad en inventario',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo para venta',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `sku`, `name`, `description`, `price`, `main_image`, `stock`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 10, 'CMU-1', 'Camiseta básica negra', 'Camiseta 100% algodón, color negro.', 80000.90, NULL, 50, 1, '2026-06-21 01:33:19', '2026-07-29 13:06:58'),
(6, 3, 'ACCH-1', 'Gorra hombre', 'Gorra de hombre con diseño moderno y ajustable para un ajuste cómodo.', 70000.00, 'Imagenes/Productos/Gorra hombre.jpg', 22, 1, '2026-07-09 13:54:54', '2026-08-13 15:04:04'),
(7, 3, 'ACCH-2', 'Gafas de sol', ' Gafas de sol con protección UV y diseño moderno.', 60000.00, 'Imagenes/Productos/gafas de sol.jpg', 60, 1, '2026-07-09 13:59:54', '2026-07-29 14:30:02'),
(8, 3, 'ACCH-3', 'Cinturon', 'inturon de cuero con diseño moderno y ajustable para un ajuste cómodo.', 45000.00, 'Imagenes/Productos/Cinturon.jpg', 15, 1, '2026-07-09 14:42:23', '2026-07-29 14:30:14'),
(9, 3, 'ACCH-4', 'Billetera', 'billetera de cuero con diseño moderno y ajustable para un ajuste cómodo.', 50000.00, 'Imagenes/Productos/Billetera.jpg', 15, 1, '2026-07-09 14:46:10', '2026-07-29 14:30:29'),
(10, 3, 'ACCH-5', 'Gorro', 'Gorro de hombre con diseño moderno y ajustable para un ajuste cómodo.', 65000.00, 'Imagenes/Productos/Gorro.jpg', 20, 1, '2026-07-09 14:48:40', '2026-07-29 14:30:42'),
(11, 4, 'CAH-1', 'Camisas Oversize', 'Camisa de hombre con diseño moderno y diseño oversize para un estilo casual y cómodo.', 80000.00, 'Imagenes/Productos/Camisa oversize.jpg', 16, 1, '2026-07-09 14:53:21', '2026-07-29 14:30:57'),
(12, 4, 'CAH-2', 'Camisas uni color', 'Camisa de hombre con diseño moderno y color unico para un estilo casual y cómodo.', 60000.00, 'Imagenes/Productos/Camisas uni color.jpg', 16, 1, '2026-07-09 14:54:56', '2026-07-29 14:31:26'),
(13, 4, 'CAH-3', 'Camibuso overside', 'Camibuso de hombre con diseño moderno y color unico para un estilo casual y cómodo.', 100000.00, 'Imagenes/Productos/camibuso oversize.jpg', 16, 1, '2026-07-09 14:56:10', '2026-07-29 14:31:48'),
(14, 4, 'CAH-4', 'Camisas polo', 'Camisas polo de hombre con diseño moderno y color unico para un estilo casual y cómodo.', 95000.00, 'Imagenes/Productos/camisas cuello polo.jpg', 16, 1, '2026-07-09 14:57:14', '2026-07-29 14:31:57'),
(15, 4, 'CAH-5', 'Camisas deportivas ', 'Camisa deportiva con gran comodidad y diseño.', 75000.00, 'Imagenes/Productos/Camisas deportivas.jpg', 13, 1, '2026-07-09 15:00:08', '2026-07-29 14:32:06'),
(16, 5, 'PH-1', 'Pantalon ancho con rotos', 'Pantalón BAGGY con Rotos Y diseño moderno y cómodo.', 120000.00, 'Imagenes/Productos/Pantalon ancho con rotos.jpg', 12, 1, '2026-07-09 15:06:14', '2026-07-29 14:32:56');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_images`
--

CREATE TABLE `product_images` (
  `image_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id imagen',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> products.product_id (1 producto puede tener muchas imágenes)',
  `url` varchar(300) NOT NULL COMMENT 'Ruta/URL de la imagen',
  `alt_text` varchar(150) DEFAULT NULL COMMENT 'Texto alternativo',
  `is_primary` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=imagen principal',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Imágenes asociadas a productos';

--
-- Volcado de datos para la tabla `product_images`
--

INSERT INTO `product_images` (`image_id`, `product_id`, `url`, `alt_text`, `is_primary`, `created_at`) VALUES
(1, 1, '/images/products/tshirt_black_main.jpg', 'Camiseta negra vista frontal', 1, '2026-06-21 01:33:19'),
(2, 1, '/images/products/tshirt_black_back.jpg', 'Camiseta negra vista posterior', 0, '2026-06-21 01:33:19');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `product_variants`
--

CREATE TABLE `product_variants` (
  `variant_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: id variante',
  `product_id` int(10) UNSIGNED NOT NULL COMMENT 'FK -> products.product_id (muchas variantes por producto)',
  `sku` varchar(80) DEFAULT NULL COMMENT 'SKU variante (opcional)',
  `size` varchar(30) DEFAULT NULL COMMENT 'Talla ej. S, M, L',
  `color` varchar(50) DEFAULT NULL COMMENT 'Color ej. negro',
  `price` decimal(10,2) DEFAULT NULL COMMENT 'Precio específico variante (si aplica)',
  `stock` int(11) NOT NULL DEFAULT 0 COMMENT 'Stock por variante'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Variantes de producto (talla, color)';

--
-- Volcado de datos para la tabla `product_variants`
--

INSERT INTO `product_variants` (`variant_id`, `product_id`, `sku`, `size`, `color`, `price`, `stock`) VALUES
(2, 1, 'SW-TSHIRT-BLK-001-M', 'M', 'negro', 29.90, 50),
(4, 6, 'GORRA-HOMBRE-S-ROJA', 'S', 'ROJO', 70000.00, 8),
(5, 6, 'GORRA-HOMBRE-S-ROJA', 'L', 'ROJO', 70000.00, 5),
(6, 6, 'GORRA-HOMBRE-S-ROJA', 'M', 'ROJO', 70000.00, 9);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `role_id` tinyint(3) UNSIGNED NOT NULL COMMENT 'PK: identificador único de rol',
  `name` varchar(30) NOT NULL COMMENT 'Nombre del rol, ej. admin, cliente',
  `description` varchar(255) DEFAULT NULL COMMENT 'Descripción del rol'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Roles de usuario (1:N con usuarios)';

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`role_id`, `name`, `description`) VALUES
(1, 'admin', 'Administrador del sistema con todos los privilegios'),
(2, 'cliente', 'Usuario final que compra en la plataforma');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `shipments`
--

CREATE TABLE `shipments` (
  `shipment_id` bigint(20) UNSIGNED NOT NULL COMMENT 'PK: id envío',
  `order_id` bigint(20) UNSIGNED NOT NULL COMMENT 'FK -> orders.order_id (1:N: un pedido puede tener varios envíos)',
  `carrier` varchar(100) DEFAULT NULL COMMENT 'Empresa de mensajería',
  `tracking_number` varchar(120) DEFAULT NULL COMMENT 'Número de seguimiento',
  `status` varchar(50) NOT NULL DEFAULT 'pendiente' COMMENT 'Estado del envío',
  `shipped_at` datetime DEFAULT NULL COMMENT 'Fecha envío',
  `delivered_at` datetime DEFAULT NULL COMMENT 'Fecha entrega'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Información de envíos y seguimiento';

--
-- Volcado de datos para la tabla `shipments`
--

INSERT INTO `shipments` (`shipment_id`, `order_id`, `carrier`, `tracking_number`, `status`, `shipped_at`, `delivered_at`) VALUES
(1, 1, 'EnvíosExpress', 'TRK123456789', 'en_proceso', '2026-06-20 20:33:19', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `user_id` int(10) UNSIGNED NOT NULL COMMENT 'PK: identificador único de usuario',
  `role_id` tinyint(3) UNSIGNED NOT NULL COMMENT 'FK -> roles.role_id (relación muchos usuarios a un rol)',
  `first_name` varchar(60) NOT NULL COMMENT 'Nombre(s) del usuario',
  `last_name` varchar(60) NOT NULL COMMENT 'Apellido(s) del usuario',
  `email` varchar(150) NOT NULL COMMENT 'Correo electrónico (único)',
  `phone` varchar(20) DEFAULT NULL COMMENT 'Número de teléfono',
  `password_hash` varchar(255) NOT NULL COMMENT 'Hash seguro de contraseña (bcrypt/argon2 recomendado)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=activo,0=desactivado',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Fecha de creación',
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Fecha última modificación'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish_ci COMMENT='Usuarios del sistema (clientes y administradores)';

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`user_id`, `role_id`, `first_name`, `last_name`, `email`, `phone`, `password_hash`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Andres', 'Gutierrez', 'andres.gutierrez@example.com', '+571300000001', '$2y$12$examplehashadmin', 1, '2026-06-21 01:33:19', '2026-06-21 01:33:19'),
(2, 2, 'Laura', 'Castellanos', 'laura.castellanos@example.com', '+571300000002', '$2y$12$examplehashuser', 1, '2026-06-21 01:33:19', '2026-06-21 01:33:19'),
(3, 2, 'Mateo', 'Ramos', 'mateo.ramos@example.com', '+571300000003', '$2y$12$examplehashuser2', 1, '2026-06-21 01:33:19', '2026-06-21 01:33:19'),
(5, 1, 'mateo', 'orjuela', 'mateo22@gmail.com', '198189', '$2y$10$mp3.ysQoHwg6sRLDpSdf.e/bzkABL/HPJOId26moZKerHl0.8gQSG', 1, '2026-07-04 20:14:32', '2026-07-04 20:14:46'),
(6, 1, 'elver', 'galarga', '123@gmail.com', '456156', '$2y$10$jt96GG4Z.W61oyA/TzBtVOJuA.gCetLLm30EtPvwuyewMvSy9/48W', 1, '2026-07-08 12:51:24', '2026-07-08 12:51:53'),
(10, 1, 'Daniel', 'Ramos', 'ramososrjueladanielesteban@gmail.com', '3045528954', '$2y$10$SSaKQO7l2zXyVp2Ii5DxLO/1FkzyXi7MNqbEf24H3VyU39iuvQr9a', 1, '2026-07-29 15:16:05', '2026-07-29 15:16:33');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_audit_user` (`user_id`);

--
-- Indices de la tabla `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`cart_id`),
  ADD UNIQUE KEY `ux_carts_user` (`user_id`) COMMENT '1:1 lógico usuario-carrito activo',
  ADD KEY `idx_carts_user` (`user_id`);

--
-- Indices de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD UNIQUE KEY `ux_cart_variant_unique` (`cart_id`,`variant_id`),
  ADD KEY `idx_cart_items_cart` (`cart_id`),
  ADD KEY `idx_cart_items_variant` (`variant_id`);

--
-- Indices de la tabla `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `ux_categories_slug` (`slug`),
  ADD KEY `idx_categories_parent` (`parent_id`);

--
-- Indices de la tabla `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_contact_user` (`user_id`);

--
-- Indices de la tabla `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `ux_coupons_code` (`code`);

--
-- Indices de la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD UNIQUE KEY `ux_invoices_number` (`invoice_number`),
  ADD KEY `idx_invoices_order` (`order_id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `idx_orders_status_created` (`status`,`created_at`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `idx_order_items_order` (`order_id`),
  ADD KEY `idx_order_items_variant` (`variant_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD UNIQUE KEY `ux_products_sku` (`sku`),
  ADD KEY `idx_products_category` (`category_id`),
  ADD KEY `idx_products_name_price` (`name`(80),`price`);

--
-- Indices de la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_product_images_product` (`product_id`);

--
-- Indices de la tabla `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`variant_id`),
  ADD KEY `idx_variant_product` (`product_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `ux_roles_name` (`name`);

--
-- Indices de la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`shipment_id`),
  ADD KEY `idx_shipments_order` (`order_id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `ux_users_email` (`email`),
  ADD KEY `fk_users_role_idx` (`role_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id log', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `carts`
--
ALTER TABLE `carts`
  MODIFY `cart_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id carrito', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `cart_item_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id item en carrito', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id categoría', AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id mensaje', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id cupón', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `invoices`
--
ALTER TABLE `invoices`
  MODIFY `invoice_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id factura', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id del pedido', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id línea pedido', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id producto', AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `product_images`
--
ALTER TABLE `product_images`
  MODIFY `image_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id imagen', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `variant_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id variante', AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: identificador único de rol', AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `shipments`
--
ALTER TABLE `shipments`
  MODIFY `shipment_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: id envío', AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'PK: identificador único de usuario', AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `fk_carts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `fk_cart_items_cart` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`cart_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `fk_categories_parent` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `fk_contact_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `fk_invoices_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_order_items_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`variant_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `fk_products_category` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variant_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `fk_shipments_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON UPDATE CASCADE;

--
-- Filtros para la tabla `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_users_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
