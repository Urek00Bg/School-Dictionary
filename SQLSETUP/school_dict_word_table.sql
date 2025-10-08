create table if not exists words (
  id int auto_increment primary key,
  word varchar(255) not null,
  definition text not null,
  created_at timestamp default current_timestamp,
  created_by int null,
  created_by_name varchar(50) not null,
  edited_at datetime default null,
  foreign key (created_by) references administrators(id) on delete set null
);
/* sl = second language */
ALTER TABLE words ADD COLUMN IF NOT EXISTS word_sl VARCHAR(255) after word; 
ALTER TABLE words ADD COLUMN IF NOT EXISTS definition_sl TEXT after definition;
ALTER TABLE words ADD COLUMN IF NOT EXISTS example_sent VARCHAR(255) after definition_sl;
ALTER TABLE words MODIFY word_sl VARCHAR(255) DEFAULT NULL;
ALTER TABLE words MODIFY definition_sl TEXT DEFAULT NULL;
ALTER TABLE words MODIFY example_sent VARCHAR(255) DEFAULT NULL;
ALTER TABLE words ADD COLUMN grade TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER example_sent;