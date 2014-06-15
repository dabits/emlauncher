drop table if exists `application_tester`;
create table `application_tester` (
  `id` integer not null auto_increment,
  `app_id` integer not null,
  `tester_mail` varchar(255) not null,
  key `idx_app` (`app_id`),
  unique key `idx_tester_app` (`tester_mail`,`app_id`),
  primary key (`id`)
)Engine=InnoDB default charset=utf8;