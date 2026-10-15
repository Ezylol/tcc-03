USE pixelswap;

INSERT INTO users (name, email, password_hash, avatar) VALUES
('Avery Stone', 'avery@pixelswap.dev', '$2y$10$abcdefghijklmnopqrstuu', 'pixel-avatar.png'),
('Mina Vale', 'mina@pixelswap.dev', '$2y$10$abcdefghijklmnopqrstuu', 'pixel-avatar.png'),
('Rafael Cruz', 'rafael@pixelswap.dev', '$2y$10$abcdefghijklmnopqrstuu', 'pixel-avatar.png');

INSERT INTO games (title, slug, developer, genre, description, release_date, cover_image, banner_image, price, is_featured) VALUES
('Hollow Knight', 'hollow-knight', 'Team Cherry', 'Action', 'Uma aventura metroidvania em um reino subterrâneo repleto de criaturas e mistérios.', '2017-02-24', 'hollow.jpg', 'hollow-banner.jpg', 24.90, 1),
('Celeste', 'celeste', 'Maddy Makes Games', 'Platformer', 'Uma jornada emocional sobre desafios, superação e coragem.', '2018-01-25', 'celeste.jpg', 'celeste-banner.jpg', 19.90, 1),
('Stardew Valley', 'stardew-valley', 'ConcernedApe', 'Simulation', 'Cultive, pesque, crie animais e construa uma vida rural feliz.', '2016-02-26', 'stardew.jpg', 'stardew-banner.jpg', 29.90, 1),
('Terraria', 'terraria', 'Re-Logic', 'Adventure', 'Explore cavernas, construções e perigos em um mundo gerado proceduralmente.', '2011-05-16', 'terraria.jpg', 'terraria-banner.jpg', 19.99, 0),
('Dead Cells', 'dead-cells', 'Motion Twin', 'Action', 'Um rogue-lite frenético com combate preciso, progressão intensa e desafiadores chefes.', '2018-08-07', 'deadcells.jpg', 'deadcells-banner.jpg', 21.90, 1),
('Undertale', 'undertale', 'Toby Fox', 'RPG', 'Uma jornada original com combate estratégico, narrativa profunda e personagens memoráveis.', '2015-09-15', 'undertale.jpg', 'undertale-banner.jpg', 17.90, 0),
('Rain World', 'rain-world', 'Videocult', 'Adventure', 'Sobreviva em um ambiente hostil, explore a paisagem e lembre-se do que significa viver.', '2017-03-28', 'rainworld.jpg', 'rainworld-banner.jpg', 22.50, 0),
('OMORI', 'omori', 'OMOCAT', 'Adventure', 'Uma história introspectiva que mistura realidade, fantasia e emoções.', '2020-12-25', 'omori.jpg', 'omori-banner.jpg', 31.90, 1),
('The Binding of Isaac: Rebirth', 'binding-of-isaac-rebirth', 'Nicalis', 'RPG', 'Um jogo de dungeon crawling que combina ação, humor e caos.', '2014-11-04', 'isaac.jpg', 'isaac-banner.jpg', 18.40, 0);

INSERT INTO game_keys (game_id, owner_user_id, key_code, status) VALUES
(1, 1, 'PKSW-HKN-001-ALPHA', 'available'),
(2, 1, 'PKSW-CEL-002-BETA', 'available'),
(3, 2, 'PKSW-STD-003-GAMMA', 'available'),
(4, 2, 'PKSW-TRR-004-DELTA', 'available'),
(5, 3, 'PKSW-DC-005-EPSILON', 'available'),
(6, 3, 'PKSW-UND-006-ZETA', 'available'),
(7, 1, 'PKSW-RW-007-ETA', 'available'),
(8, 2, 'PKSW-OMR-008-THETA', 'available'),
(9, 3, 'PKSW-ISA-009-IOTA', 'available');

INSERT INTO listings (seller_id, game_id, listing_type, key_id, price, accepts_trade, status) VALUES
(1, 1, 'sale', 1, 18.90, 1, 'active'),
(2, 3, 'trade', 3, 29.90, 1, 'active'),
(3, 5, 'sale', 5, 21.90, 0, 'active'),
(1, 7, 'trade', 7, 22.50, 1, 'active');

INSERT INTO wishlists (user_id, game_id) VALUES
(1, 2),
(1, 5),
(2, 1),
(3, 8);
