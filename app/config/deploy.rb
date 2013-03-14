set :application, "PodCatcher"
set :domain,      "root@192.168.1.5"
set :deploy_to,   "/var/www/#{application}"
set :app_path,    "app"
set :use_sudo,    false
set :user,        "root"
set :repository,  "git@github.com:EddyLane/PodCatcher.git"
set :scm,         :git

set :writable_dirs,     ["app/cache", "app/logs"]
set :webserver_user,    "www-data"
set :permission_method, :acl

# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

before "deploy:restart", "deploy:set_permissions"

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set  :keep_releases,  3

# Be more verbose by uncommenting the following line
logger.level = Logger::MAX_LEVEL
