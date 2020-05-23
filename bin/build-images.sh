#!/bin/bash
# Fail if anything inside the script fails.
set -e

# Login to docker hub.
echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USERNAME" --password-stdin

# Create images.
# php command.php

# Commit Updated images.

for directory in $(find images/* -maxdepth 0 -type d ); 
do
	# Get version number of the directory.
    VERSION_NUMBER=${directory/images\/}
    
    # Build the image
    docker build -t nikschavan/psalm-docker:$VERSION_NUMBER $directory
    docker images

    # Push the image to Docker hub.
    docker push nikschavan/psalm-docker:$VERSION_NUMBER
done