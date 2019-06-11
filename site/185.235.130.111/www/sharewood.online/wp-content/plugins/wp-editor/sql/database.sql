create table if not exists `[prefix]settings` (
  `key` varchar(50) not null,
  `value` text not null,
  primary key(`key`)
);