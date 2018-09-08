-- MySQL dump 10.16  Distrib 10.1.35-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: test_eng
-- ------------------------------------------------------
-- Server version	10.1.34-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `progress`
--

DROP TABLE IF EXISTS `progress`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `progress` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vocabulary_id` int(11) NOT NULL,
  `summary` int(11) DEFAULT NULL,
  `success` int(11) DEFAULT NULL,
  `errors` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `vocabulary_id` (`vocabulary_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=166 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progress`
--

LOCK TABLES `progress` WRITE;
/*!40000 ALTER TABLE `progress` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `progress850`
--

DROP TABLE IF EXISTS `progress850`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `progress850` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `vocabulary_id` int(11) DEFAULT NULL,
  `summary` int(11) DEFAULT NULL,
  `success` int(11) DEFAULT NULL,
  `errors` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1081 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `progress850`
--

LOCK TABLES `progress850` WRITE;
/*!40000 ALTER TABLE `progress850` DISABLE KEYS */;
/*!40000 ALTER TABLE `progress850` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `last_visit` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `today_count` int(4) NOT NULL DEFAULT '150',
  `type_vocabulary` smallint(6) NOT NULL DEFAULT '850',
  `login` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hash` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vocabulary`
--

DROP TABLE IF EXISTS `vocabulary`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vocabulary` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eng` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ru` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10841 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vocabulary`
--

LOCK TABLES `vocabulary` WRITE;
/*!40000 ALTER TABLE `vocabulary` DISABLE KEYS */;
INSERT INTO `vocabulary` VALUES (5845,'A good deal','Много'),(5846,'Abandon','Оставить, забросить'),(5847,'Abate','Уменьшаться, ослабевать'),(5849,'Abide','Смириться, выносить'),(5850,'Able','Способный, могущий'),(5851,'Abnormal','Ненормальный, аномальный'),(5852,'Aboard','На борту'),(5853,'Abolish','Упразднять'),(5854,'Abort','Прерывать'),(5855,'Abound','Изобиловать'),(5856,'About','Примерно'),(5857,'Abroad','За границу'),(5858,'Abrupt','Крутой, резкий'),(5859,'Absent','Отсутствующий'),(5860,'Absorb','Впитывать, поглощать'),(5861,'Abundant','Обильный'),(5864,'Abuse','Насилие, домогательство'),(5865,'Accelerate','Ускорять'),(5866,'Accept','Принять'),(5867,'Access','Доступ'),(5868,'Accessory','Принадлежность, аксессуар'),(5870,'Accident','Происшествие, напр.  ДТП'),(5871,'Acclaim','Приветствовать, аплодировать'),(5872,'Accommodate','Размещать, вмещать'),(5873,'Accompany','Сопровождать'),(5874,'Accomplish','Выполнить, завершить'),(5875,'Accord','Согласие'),(5876,'According to','В соответствии'),(5878,'Account for','Объяснить (произошедшее)'),(5879,'Account','Счёт (в банке)'),(5882,'Accustom','Приучить'),(5883,'Ache','Боль, болеть'),(5885,'Acid','Кислота'),(5886,'Acknowledge','Признавать, подтверждать'),(5887,'Acquaint','Знакомить'),(5888,'Acquire','Обретать'),(5889,'Acquit','Оправдать (в суде)'),(5890,'Across','За, через'),(5891,'Act','Действовать'),(5892,'Act on','Действовать в соответствии'),(5893,'Act up','Не работать как надо'),(5895,'Adamant','Непреклонный'),(5897,'Add up','Сходиться (об информации)'),(5898,'Addict','Наркоман, привыкать'),(5899,'Addition','Добавление, сложение'),(5900,'Adhere','Придерживаться'),(5901,'Adjacent','Смежный, прилегающий'),(5902,'Adjective','Прилагательное'),(5903,'Adjust','Настраивать, поправлять и т.д.'),(5904,'Admire','Восхищаться'),(5905,'Admit','Признавать, допускать'),(5906,'Adolescent','Подросток, подростковый'),(5908,'Adopt','Усыновить или взять (животное)'),(5909,'Adore','Обожать'),(5911,'Adult','Взрослый'),(5912,'Adultery','Прелюбодеяние, неверность'),(5913,'Advance','Продвигаться, продвижение'),(5914,'Advantage','Преимущество'),(5915,'Adventure','Приключение'),(5916,'Adverb','Наречие'),(5917,'Adversary','Противник, соперник'),(5918,'Adverse','Неблагоприятный'),(5919,'Advertise','Рекламировать'),(5920,'Advice','Совет (неисчисл!)'),(5921,'Advise','Советовать'),(5922,'Advocate','Защищать, отстаивать'),(5924,'Aerial','Антенна'),(5926,'Affair','Роман, любовная история'),(5927,'Affect','Воздействовать'),(5928,'Affection','Привязанность, влечение'),(5929,'Affiliate','Филиал, отделение, мл. партнёр'),(5930,'Affirm','Утверждать, подтверждать'),(5931,'Afflict','Поражать, вредить'),(5932,'Affluent','Обеспеченный, состоятельный'),(5933,'Afford','Позволить себе'),(5934,'Afraid','Напуганный'),(5935,'After all','Всё-таки, в конце концов'),(5936,'Aftermath','Последствие, результат'),(5938,'Age','Век, эпоха'),(5939,'Agenda','Повестка дня'),(5940,'Aggravate','Обострять'),(5941,'Aggregate','Совокупный'),(5942,'Agile','Проворный, гибкий'),(5943,'Agitate','Волновать'),(5945,'Agreement','Соглашение, договор'),(5946,'Agriculture','Сельское хозяйство'),(5947,'Ahead','Вперёд, впереди'),(5948,'Aid','Помощь, помогать'),(5949,'Ailment','Недомогание, болезнь'),(5951,'Air','Вид, впечатление'),(5952,'Airborne','Находящийся в воздухе'),(5953,'Aircraft','Самолёт, авиация'),(5954,'Airline','Авиалиния'),(5955,'Aisle','Проход'),(5957,'Alarm','Тревога, сигнализация'),(5958,'Alas','Увы'),(5960,'Alert','Тревога, бдительный'),(5961,'Alien','Чужак, пришелец, иммигрант'),(5962,'Alienate','Отчуждать, отдалять, отвергать'),(5963,'Align','Выравнивать, выстраивать'),(5964,'Alike','Подобно'),(5965,'Alive','Живой'),(5966,'All along','Всё время'),(5967,'Allege','Утверждать, приписывать'),(5969,'Allocate','Распределять (ресурсы)'),(5970,'Allot','Выделять, отводить'),(5972,'Allow for','Выделять, рассчитывать'),(5973,'Allowance','Выплата, пособие'),(5974,'Allude','Намекать, указывать'),(5975,'Allure','Очарование, очаровывать'),(5976,'Ally','Союзник'),(5977,'Almost','Почти'),(5978,'Alone','В одиночестве'),(5982,'Alternate','Чередовать, чередующийся'),(5983,'Although','Хотя и'),(5984,'Altitude','Высота'),(5985,'Altogether','В целом'),(5986,'Amateur','Любитель, любительский'),(5987,'Amaze','Поражать, изумлять'),(5988,'Ambassador','Посол'),(5989,'Amber','Янтарь'),(5990,'Ambiguous','Двусмысленный, неоднозначный'),(5991,'Ambulance','Скорая помощь'),(5992,'Ambush','Засада'),(5993,'Amend','Исправить, улучшить'),(5994,'Amenity','Удобство, обустройство'),(5995,'Amid','Среди'),(5996,'Ammunition','Боеприпасы'),(5997,'Amount','Количество (неисчисл.)'),(5998,'Ample','Обильный, широкий'),(5999,'Amplify','Усиливать'),(6000,'Amuse','Развлечь, порадовать');
/*!40000 ALTER TABLE `vocabulary` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vocabulary850`
--

DROP TABLE IF EXISTS `vocabulary850`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `vocabulary850` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `eng` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ru` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=844 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vocabulary850`
--

LOCK TABLES `vocabulary850` WRITE;
/*!40000 ALTER TABLE `vocabulary850` DISABLE KEYS */;
INSERT INTO `vocabulary850` VALUES (1,'Angle','Угол, поворачивать'),(2,'Ant','Муравей'),(3,'Apple','Яблоко'),(4,'Arch','Арка, дуга, выгибать'),(5,'Arm','Рука, вооружать'),(6,'Army','Армия'),(7,'Bag','Сумка'),(8,'Ball','Мяч'),(9,'Bank','Банк'),(10,'Basin','Бассейн'),(11,'Basket','Корзина'),(12,'Bath','Ванна, купаться'),(13,'Bed','Кровать'),(14,'Bee','Пчела'),(15,'Bell','Колокольчик'),(16,'Berry','Ягода'),(17,'Bird','Птица'),(18,'Blade','Лезвие'),(19,'Board','Доска'),(20,'Boat','Лодка, судно'),(21,'Bone','Кость'),(22,'Book','Книга'),(23,'Boot','Ботинок, загружать'),(24,'Bottle','Бутылка'),(25,'Box','Коробка'),(26,'Boy','Мальчик'),(27,'Brain','Мозг'),(28,'Brake','Тормоз, тормозить'),(29,'Branch','Ветвь, отделение'),(30,'Brick','Кирпич'),(31,'Bridge','Мост'),(32,'Brush','Щётка, кисть'),(33,'Bucket','Ведро, черпать'),(34,'Bulb','Луковица, выпирать'),(35,'Button','Кнопка, застёгивать'),(36,'Baby','Ребёнок, младенец'),(37,'Cake','Пирог'),(38,'Camera','Камера'),(39,'Card','Карта, чесать'),(40,'Cart','Везти'),(41,'Carriage','Вагон'),(42,'Cat','Кот'),(43,'Chain','Цепь, сеть'),(44,'Cheese','Сыр'),(45,'Chest','Грудь, сундук'),(46,'Chin','Подбородок'),(47,'Church','Церковь'),(48,'Circle','Круг'),(50,'Cloud','Облако'),(51,'Coat','Пальто, покрывать'),(52,'Collar','Воротник, хватать'),(53,'Comb','Расчёсывать'),(54,'Cord','Шнур, связывать'),(55,'Cow','Корова'),(56,'Cup','Чашка'),(57,'Curtain','Занавес, штора, занавешивать'),(58,'Cushion','Подушка, смягчать'),(59,'Dog','Собака'),(60,'Door','Дверь'),(61,'Drain','Утечка, истощать'),(62,'Drawer','Ящик'),(63,'Dress','Платье, одевать'),(64,'Drop','Капля, опускать'),(65,'Ear','Ухо'),(66,'Egg','Яйцо'),(67,'Engine','Двигатель'),(68,'Eye','Глаз'),(69,'Face','Лицо'),(70,'Farm','Ферма'),(71,'Feather','Перо, украшать'),(72,'Finger','Палец'),(73,'Fish','Рыба'),(74,'Flag','Флаг, сигнализировать'),(75,'Floor','Пол, этаж, дно'),(76,'Fly','Муха, лететь'),(78,'Fork','Вилка'),(79,'Fowl','Домашняя птица'),(80,'Frame','Структура, рамка, создавать'),(81,'Garden','Сад'),(82,'Girl','Девочка'),(83,'Glove','Перчатка'),(84,'Goat','Коза'),(85,'Gun','Оружие'),(86,'Hair','Волосы'),(87,'Hammer','Молоток'),(88,'Hand','Рука'),(89,'Hat','Шляпа'),(90,'Head','Голова'),(91,'Heart','Сердце'),(92,'Hook','Крюк, вербовать'),(93,'Horn','Рожок'),(94,'Horse','Лошадь'),(95,'Hospital','Больница'),(96,'House','Дом'),(97,'Island','Остров'),(99,'Kettle','Чайник'),(100,'Key','Ключ'),(101,'Knee','Колено'),(102,'Knife','Нож'),(103,'Knot','Узел'),(104,'Leaf','Лист, покрывать листвой'),(105,'Leg','Нога'),(106,'Library','Библиотека'),(107,'Line','Линия, очередь, выравнивать'),(108,'Lip','Губа'),(109,'Lock','Замок'),(110,'Map','Карта'),(111,'Match','Спичка, сделки, соответствовать'),(112,'Monkey','Обезьяна'),(113,'Moon','Луна'),(114,'Mouth','Рот, жевать'),(115,'Muscle','Мускул'),(116,'Nail','Ноготь'),(117,'Neck','Шея, обниматься'),(118,'Needle','Игла'),(119,'Nerve','Нерв'),(120,'Net','Чистый, сеть'),(121,'Nose','Нос'),(122,'Nut','Орех'),(123,'Office','Офис'),(124,'Orange','Апельсин, оранжевый'),(125,'Oven','Духовка, печь'),(126,'Parcel','Пакет, распределять'),(127,'Pen','Ручка'),(128,'Pencil','Карандаш'),(129,'Picture','Картина'),(130,'Pig','Свинья'),(131,'Pin','Булавка, прикреплять'),(132,'Pipe','Труба'),(133,'Plane','Самолёт'),(134,'Plate','Пластина'),(135,'Plow','Плуг, пахать'),(136,'Pocket','Карман, присваивать'),(137,'Pot','Горшок'),(138,'Potato','Картофель'),(139,'Prison','Тюрьма'),(140,'Pump','Насос, качать'),(141,'Rail','Рельс, перевозить поездом'),(142,'Rat','Крыса'),(143,'Receipt','Квитанция'),(144,'Ring','Кольцо, звонить'),(145,'Rod','Прут'),(146,'Roof','Крыша'),(147,'Root','Корень'),(148,'Sail','Парус'),(149,'School','Школа'),(150,'Scissors','Ножницы'),(151,'Screw','Винт'),(152,'Seed','Семя'),(153,'Sheep','Овцы'),(154,'Shelf','Полка'),(155,'Ship','Корабль'),(156,'Shirt','Рубашка'),(157,'Shoe','Ботинок'),(158,'Skin','Кожа, очищать'),(159,'Skirt','Юбка'),(160,'Snake','Змея'),(161,'Sock','Носок'),(162,'Spade','Лопата'),(163,'Sponge','Губка'),(164,'Spoon','Ложка'),(165,'Spring','Весна'),(166,'Square','Квадрат'),(167,'Stamp','Печать, марка, отпечатывать'),(168,'Star','Звезда'),(169,'Station','Станция'),(170,'Stem','Стебель, происходить'),(171,'Stick','Палка, прикреплять'),(172,'Stocking','Снабжать'),(173,'Stomach','Живот, смелость, переваривать'),(174,'Store','Магазин, запас'),(175,'Street','Улица'),(176,'Sun','Солнце'),(177,'Table','Стол'),(178,'Tail','Хвост, выслеживать'),(179,'Thread','Нить, пронизывать'),(180,'Throat','Горло'),(181,'Thumb','Листать, большой палец'),(182,'Ticket','Билет'),(183,'Toe','Палец ноги'),(185,'Tooth','Зуб'),(186,'Town','Город'),(187,'Train','Поезд'),(188,'Tray','Поднос'),(189,'Tree','Дерево'),(190,'Trousers','Брюки'),(191,'Umbrella','Зонт'),(192,'Wall','Стена'),(193,'Watch','Часы'),(194,'Wheel','Колесо, вертеть'),(195,'Whip','Кнут, хлестать'),(196,'Whistle','Свист, свистеть'),(197,'Window','Окно'),(198,'Wing','Крыло'),(199,'Wire','Провод'),(200,'Worm','Червь');
/*!40000 ALTER TABLE `vocabulary850` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2018-09-07 23:07:04
