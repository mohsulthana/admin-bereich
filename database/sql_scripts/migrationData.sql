-- Articles
INSERT INTO `articles`(`id`, `description`, `name`, `imagepath`, `hasOrigin`) VALUES (1,'Die Gemüse-Box ist ein Abonnement, bei dem Sie regelmässig mit marktfrischem saisongerechtem Gemüse oder mit Früchten direkt nach Hause beliefert werden.','Gemüsebox','vegetablebox.jpg', 1);
INSERT INTO `articles`(`id`, `description`, `name`, `imagepath`, `hasOrigin`) VALUES (2,'Je nach Saison stellen wir ein auf Ihren Betrieb abgstummenes Sortiment an frischen Früchten zusammen.','Früchtebox','fruitbox.jpg', 1);
INSERT INTO `articles`(`id`, `description`, `name`, `imagepath`, `hasOrigin`) VALUES (3,'Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam.','Eierbox','eggbox.jpg', 0);

-- Articletypes
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (1,3,2.20,'4 Eier',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (2,3,3.30,'6 Eier',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (3,3,5.50,'8 Eier',0,1);

INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (4,2,15,'15.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (5,2,20,'20.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (6,2,25,'25.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (7,2,30,'30.-',0,1);

INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (8,1,15,'15.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (9,1,20,'20.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (10,1,25,'25.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (11,1,30,'30.-',0,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (12,1,40,'40.-',1,1);
INSERT INTO `articletypes`(`id`, `article_id`, `price`, `description`, `onlyAdmin`, `isActive`) VALUES (13,1,50,'50.-',1,1);

-- Origins
INSERT INTO `origins`(`id`, `name`) VALUES (1, "Schweiz");
INSERT INTO `origins`(`id`, `name`) VALUES (2, "Saisonal Import");
INSERT INTO `origins`(`id`, `name`) VALUES (3, "Egal");
INSERT INTO `origins`(`id`, `name`) VALUES (4, "Seeländer Eier");

-- Regions
INSERT INTO `regions`(`id`, `name`) VALUES (1,"Montag wöchentlich");
INSERT INTO `regions`(`id`, `name`) VALUES (2,"Montag 14 tägig gerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (3,"Montag 14 tägig ungerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (4,"Montag A");
INSERT INTO `regions`(`id`, `name`) VALUES (5,"Montag B");
INSERT INTO `regions`(`id`, `name`) VALUES (6,"Montag C");
INSERT INTO `regions`(`id`, `name`) VALUES (7,"Montag D");
INSERT INTO `regions`(`id`, `name`) VALUES (8,"Spezial Abos");
INSERT INTO `regions`(`id`, `name`) VALUES (9,"Dienstag");
INSERT INTO `regions`(`id`, `name`) VALUES (10,"Mittwoch wöchentlich");
INSERT INTO `regions`(`id`, `name`) VALUES (11,"Mittwoch gerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (12,"Mittwoch ungerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (13,"Donnerstag wöchentlich");
INSERT INTO `regions`(`id`, `name`) VALUES (14,"Donnerstag gerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (15,"Donnerstag ungerade Wochen");
INSERT INTO `regions`(`id`, `name`) VALUES (16,"Donnerstag A");
INSERT INTO `regions`(`id`, `name`) VALUES (17,"Donnerstag B");
INSERT INTO `regions`(`id`, `name`) VALUES (18,"Freitag");
INSERT INTO `regions`(`id`, `name`) VALUES (19,"Samstag");
INSERT INTO `regions`(`id`, `name`) VALUES (20,"Zurückgestellte");

-- Salutations
INSERT INTO `salutations`(`id`, `name`) VALUES (1,"Herr");
INSERT INTO `salutations`(`id`, `name`) VALUES (2,"Frau");

-- Addresses
INSERT INTO `addresses`(`id`, `salutation_id`, `firstname`, `name`, `street`, `zip`, `town`) VALUES (1,1,"Patrick","Michel","Kappelenstrasse 19","3250","Lyss");
INSERT INTO `addresses`(`id`, `salutation_id`, `firstname`, `name`, `street`, `zip`, `town`) VALUES (2,1,"Luca","Mühlheim","Lindenweg 17","3273","Kappelen");

-- Users
INSERT INTO `users`(`id`, `region_id`, `username`, `password`, `isAdmin`, `isActive`, `passwordresetcode`, `passwordresetdate`, `billingAddress_id`, `shippingAddress_id`) VALUES (1,1,"patrickmichel96@yahoo.de","cc03e747a6afbbcbf8be7668acfebee5",1,1,NULL,NULL,1,NULL);
INSERT INTO `users`(`id`, `region_id`, `username`, `password`, `isAdmin`, `isActive`, `passwordresetcode`, `passwordresetdate`, `billingAddress_id`, `shippingAddress_id`) VALUES (2,1,"lucamuh@besonet.ch","cc03e747a6afbbcbf8be7668acfebee5",1,1,NULL,NULL,2,NULL);
INSERT INTO `users`(`id`, `region_id`, `username`, `password`, `isAdmin`, `isActive`, `passwordresetcode`, `passwordresetdate`, `billingAddress_id`, `shippingAddress_id`) VALUES (3,1,"info@gemuese-eggli.ch","a5cbef46a91ba57a48b6dc4fc7cba4d5",1,1,NULL,NULL,NULL,NULL);
