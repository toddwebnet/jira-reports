
CREATE USER 'jira'@'%' IDENTIFIED BY 'x8VJfCqW9SLS6JvG';
CREATE DATABASE IF NOT EXISTS `jira`;
GRANT ALL PRIVILEGES ON `jira`.* TO 'jira'@'%';GRANT ALL PRIVILEGES ON `jira\_%`.* TO 'jira'@'%';
flush privileges;
