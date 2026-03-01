# Docker Setup

## Getting started

### Prerequisites

- Docker and Docker Compose installed on your system (You might want to `sudo usermod -aG kvm $USER` and
  `sudo usermod -aG docker $USER`)
- Composer installed globally (or available via Docker)

### Initial Setup

1. **Create a new Yii2 Advanced Application**

```bash
composer create-project --prefer-dist yiisoft/yii2-app-advanced yii-application
cd yii-application
```

2. **Start the development environment**

```bash
docker compose -f docker/docker-compose.dev.yml up -d --build
```

3. **Install dependencies**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev composer install
```

4. **Initialize Yii2 Apps**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev ./init --env=Development
```

5. **Run database migrations**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev ./yii migrate
```

6. **Create default BackofficeUser**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev ./yii create-default-backoffice-user
```

You may also want to add some initial example data to the example tables fnord and foo:
```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev ./yii add-example-data
```

7. **Access the application**

- **Frontpage**: `http://localhost:20080`
- **Backoffice**: `http://localhost:20081`
- **API**: `http://localhost:20082`

### Additional Commands

**Stop the containers:**

```bash
docker compose -f docker/docker-compose.dev.yml down
```

**View logs:**

```bash
docker compose -f docker/docker-compose.dev.yml logs -f
```

**Execute commands inside the container:**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev bash
```

**Access MySQL from host machine:**

```bash
mysql -h 127.0.0.1 -P 23306 -u yii2advanced-dev -p
# Password: secret
```

**Access MySQL from within container:**

```bash
docker compose -f docker/docker-compose.dev.yml exec app-dev bash
mysql -h mysql-yii2advanced-dev -u yii2advanced-dev -p --skip-ssl
# Password: secret
```

### Other Environments

For other environments (stage, tests, prod), use the respective docker-compose file:

- **Stage**: `docker-compose.stage.yml` (ports: 30080, 30081, 30082)
- **Tests**: `docker-compose.tests.yml`
- **Production**: `docker-compose.prod.yml` (ports: 40080, 40081, 40082)

**Note**: Stage and tests environments automatically run `./init` and `composer install` during the build phase and
`./yii migrate/fresh` on startup. Stage runs also `./yii create-default-backoffice-user` and
`./yii create-default-backoffice-user` on startup.

## Environment Comparison

In all scenarios, one container is build containing all 4 Yii2 Apps (frontpage, backoffice, api and console).

While there are 3 environments defined in `/environments` (dev, stage and prod), the docker setup provides 4
docker-compose files (dev, stage, test and prod). `docker-compose.stage.yml` and `docker-compose.test.yml` both
use `/environments/stage`.

| Docker Compose                | 'dev'           | 'tests'       | 'stage'       | 'prod'        |
|-------------------------------|-----------------|---------------|---------------|---------------|
| Mount source into container   | ✓               | Copy in build | Copy in build | Copy in build |
| `./init`                      | Run manually    | During Build  | During Build  | Run manually  |
| YII_ENV                       | `dev`           | `stage`       | `stage`       | `prod`        |
| YII_DEBUG                     | `true`          | `false`       | `false`       | `false`       |
| Module 'debug'                | ✓               | -             | -             | -             |
| Module 'gii'                  | ✓               | -             | -             | -             |
| tests available               | ✓               | ✓             | -             | -             |
| `composer install`            | Run manually    | During Build  | During Build  | Run manually  |
| With dev dependencies         | ✓               | ✓             | -             | -             |
| `yii migrate`                 | Run manually    | On Startup    | On Startup    | Run manually  |
| Build in DB                   | ✓               | ✓             | ✓             | -             |
| Data Persistence              | Change manually | ⚠️ Ephemeral  | ⚠️ Ephemeral  | n/a           |
| DB port exposed to localhost  | 23306           | -             | 33306         | n/a           |
| Create default BackofficeUser | Run manually    | -             | On Startup    | -             |
| Initialize example data       | Run manually    | -             | On Startup    | -             |
| `rm -rf /var/lib/apt/lists/*` | -               | ✓             | -             | ✓             |
| Port for MailDev              | n/a             | n/a           | 1080          | n/a           |
| Port for frontpage app        | 20080           | n/a           | 30080         | 40080         |
| Port for backoffice app       | 20081           | n/a           | 30081         | 40081         |
| Port for api app              | 20082           | n/a           | 30082         | 40082         |

### Mount source code

In 'dev' the projects source code is mounted from your local machine into the container ("hot-reloading").

All other setups will copy the source code into the container during build time.

### Init

In 'tests' and 'stage' the Yii2 `./init` script will be run during the build phase of the container.

To avoid accidental override of local files or unintended configurations going live, in 'dev' and 'prod' you need to
run `./init` manually.

`YII_DEBUG` will be `true` only in 'dev'.

Tests are available in 'dev' (in `dev` with `YII_DEBUG=true`) and 'tests' (in `stage` with `YII_DEBUG=false`).

### Composer

Dependencies will be installed using `composer` automatically while building the containers for 'tests' and 'stage'.

Comment out these line in `stage/Dockerfile` and `tests/Dockerfile` if you want to preserver your current
`composer.lock` in these environments:

```
RUN rm -f composer.lock
RUN rm -rf vendor
```

You need to run `composer install --no-dev` in 'prod' or `composer update` in 'dev' manually.

### Migrations

Yii2 database migrations will be run on each startup of 'tests' and 'stage' containers on their own database.

To avoid accidental data loss, in 'dev' and 'prod' you need to run `yii migrate` manually.

### Database

For 'prod' you need to set up and configure your own persistent database. For 'tests' and 'stage' a container with a
pre-configured database will be created. For 'dev', the container exists, but it is in your obligation to decide when to
run `yii migrate` or recreate the dev-db from scratch.

|          | dev                    | dev test               | tests                    | stage                    |
|----------|------------------------|------------------------|--------------------------|--------------------------|
| Host     | mysql-yii2advanced-dev | mysql-yii2advanced-dev | mysql-yii2advanced-tests | mysql-yii2advanced-stage |
| DB Name  | yii2advanced_dev       | yii2advanced_dev_test  | yii2advanced_stage_test  | yii2advanced_stage       |
| User     | yii2advanced-dev       | yii2advanced-dev       | yii2advanced-stage       | yii2advanced-stage       |
| Password | secret                 | secret                 | secret                   | secret                   |

The password for the user `root` in all Docker containers defaults to `verysecret`.

### Optimize container size

During the build of 'tests' and 'prod' containers, `rm -rf /var/lib/apt/lists/*` will be run to optimize the container
size. To be able to use `apt` during the lifecycle of 'dev' and 'stage' containers, we accept the slightly larger size
for those containers and keep the package list.

### MailDev for Stage

The Stage Environment makes use of [MailDev](https://maildev.github.io/maildev/).

All emails can be viewed at: http://localhost:1080/#/

## General overview of common Docker commands

### Viewing Resources

**List running containers:**

```bash
docker ps
```

**List all containers (including stopped):**

```bash
docker ps -a
```

**List images:**

```bash
docker images
# or
docker image ls
```

**List volumes:**

```bash
docker volume ls
```

**View detailed information about a specific resource:**

```bash
# Inspect a container
docker inspect <container_id_or_name>

# Inspect an image
docker inspect <image_id_or_name>

# Inspect a volume
docker volume inspect <volume_name>
```

**View resource usage statistics:**

```bash
# Real-time stats for running containers
docker stats

# Disk usage summary
docker system df

# Detailed disk usage
docker system df -v
```

**List resources created by docker-compose:**

```bash
# List containers for this project
docker compose -f docker/docker-compose.dev.yml ps

# List all compose projects
docker compose ls
```

### Cleaning Up Resources

**Stop and remove containers:**

```bash
# Stop a running container
docker stop <container_id_or_name>

# Remove a stopped container
docker rm <container_id_or_name>

# Stop and remove in one command
docker rm -f <container_id_or_name>

# Stop all running containers
docker stop $(docker ps -q)

# Remove all stopped containers
docker container prune
```

**Remove images:**

```bash
# Remove a specific image
docker rmi <image_id_or_name>

# Remove all unused images
docker image prune

# Remove all images not used by any container
docker image prune -a
```

**Remove volumes:**

```bash
# Remove a specific volume
docker volume rm <volume_name>

# Remove all unused volumes (WARNING: This will delete data!)
docker volume prune
```

**Clean up everything:**

```bash
# Remove all stopped containers, unused networks, dangling images, and build cache
docker system prune

# Also remove unused volumes (WARNING: This will delete data!)
docker system prune --volumes

# Remove everything including images not used by any container
docker system prune -a --volumes
```

**Clean up this project's resources:**

```bash
# Stop and remove containers, networks (keeps volumes and images)
docker compose -f docker/docker-compose.dev.yml down

# Also remove volumes (WARNING: This will delete database data!)
docker compose -f docker/docker-compose.dev.yml down -v

# Also remove images
docker compose -f docker/docker-compose.dev.yml down --rmi all
```

### Backup and Restore

**Export and import containers:**

```bash
# Export a container's filesystem to a tar archive
docker export <container_id_or_name> > container-backup.tar

# Import from a tar archive to create an image
docker import container-backup.tar myimage:tag
```

**Save and load images:**

```bash
# Save an image to a tar archive
docker save -o image-backup.tar <image_name>:tag

# Load an image from a tar archive
docker load -i image-backup.tar
```

**Backup volumes:**

```bash
# Method 1: Using a temporary container to copy volume data
docker run --rm -v <volume_name>:/source -v $(pwd):/backup alpine tar czf /backup/volume-backup.tar.gz -C /source .

# Method 2: Copy files from a running container
docker cp <container_id>:/path/in/container ./backup-directory
```

**Restore volumes:**

```bash
# Create a new volume and restore data
docker volume create <new_volume_name>
docker run --rm -v <new_volume_name>:/target -v $(pwd):/backup alpine tar xzf /backup/volume-backup.tar.gz -C /target

# Copy files to a running container
docker cp ./backup-directory <container_id>:/path/in/container
```

**Backup MySQL database (for this project):**

```bash
# Create a SQL dump
docker compose -f docker/docker-compose.dev.yml exec mysql-yii2advanced-dev mysqldump -u yii2advanced-dev -p yii2advanced-dev > backup.sql

# Restore from SQL dump
docker compose -f docker/docker-compose.dev.yml exec -T mysql-yii2advanced-dev mysql -u yii2advanced-dev -p yii2advanced-dev < backup.sql
```

**Backup entire Docker environment:**

```bash
# Backup all volumes
for volume in $(docker volume ls -q); do
  docker run --rm -v $volume:/source -v $(pwd)/backups:/backup alpine tar czf /backup/$volume.tar.gz -C /source .
done

# Backup all images
docker save $(docker images -q) -o all-images-backup.tar
```

## CI/CD Pipeline

This project uses GitHub Actions for continuous integration. Tests run automatically on every push and pull request to `master`, `main`, and `develop` branches.

### Workflows

1. **Tests** (`.github/workflows/build.yml`)
   - Runs on multiple PHP versions (8.2, 8.3, 8.4, 8.5)
   - Tests against MySQL 8.0 and latest
   - Uses native PHP setup on Ubuntu runners
   - Uploads test output artifacts on failure

2. **Docker Tests** (`.github/workflows/docker-tests.yml`)
   - Validates the Docker test environment works correctly
   - Uses `docker-compose.tests.yml` for consistency with local testing
   - Ensures no environment-specific issues

3. **Dependency Check** (`.github/workflows/dependency-check.yml`)
   - Runs weekly (Sundays at midnight UTC)
   - Checks for security vulnerabilities via `composer audit`
   - Creates GitHub issues if vulnerabilities are found
   - Reports outdated dependencies

### Running Tests Locally

To run the same tests that run in CI:

```bash
# Using Docker (same as CI Docker workflow)
docker compose -f docker/docker-compose.tests.yml up --build --abort-on-container-exit

# Cleanup after tests
docker compose -f docker/docker-compose.tests.yml down -v
```

### Viewing Test Results

- **Pull Requests**: Test results appear in the PR checks section
- **Branch Pushes**: View results in the Actions tab of the repository
- **Failed Tests**: Download artifact `test-output-*` or `docker-test-output` to view detailed error reports

### Manual Trigger

All workflows can be triggered manually from the Actions tab:
1. Go to the Actions tab in your GitHub repository
2. Select the workflow (Tests, Docker Tests, or Dependency Check)
3. Click "Run workflow"
4. Select the branch and click "Run workflow"

### Branch Protection

To require tests to pass before merging:
1. Go to Settings > Branches in your GitHub repository
2. Add a rule for `develop` and `master`/`main`
3. Enable "Require status checks to pass before merging"
4. Select the required checks:
   - `Tests` jobs (PHP 8.2/8.3/8.4/8.5 with MySQL)
   - `Docker Test Environment`
5. Enable "Require branches to be up to date before merging"
