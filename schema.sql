DROP TABLE IF EXISTS authors;
CREATE TABLE authors (
  screenname         TEXT PRIMARY KEY,
  name               TEXT NOT NULL,
  uri                TEXT,
  profile_image_url  TEXT
);

DROP TABLE IF EXISTS entries;
CREATE TABLE entries (
  id                 INTEGER PRIMARY KEY, /* twitterのtweet id */
  published          TEXT NOT NULL,
  title              TEXT NOT NULL,
  content            BLOB,
  author             TEXT NOT NULL,
  alternate_url      TEXT NOT NULL
);


