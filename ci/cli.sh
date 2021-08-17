#!/bin/bash

#. /lib/lsb/init-functions
DIR_ROOT=$(CDPATH= cd -- "$( dirname -- "$0" )" && pwd)

cd ${DIR_ROOT}/
PROJECT_NAME="ilolly"

function make_storage_folder() {
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/contact_file
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/album
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/announce
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/avatar
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/department_icon
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/app/medicine
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/framework/cache/data
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/framework/sessions
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/framework/testing
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/framework/views
    mkdir -p ${DIR_ROOT}/storage/${PROJECT_NAME}/storage/logs
    chmod a+w -R ${DIR_ROOT}/storage/${PROJECT_NAME}/storage
}

case "$1" in
init)
    read -p "Ready to initialize environment? (y/Y) " -n 1 -r
    echo -e "\n"
    if [[ ! ${REPLY} =~ ^[Yy]$ ]]; then
      echo " =====> Task skipped."
      exit 0
    fi
    docker-compose -p "${PROJECT_NAME}" down
    rm -rf ${DIR_ROOT}/storage
    make_storage_folder
    docker-compose -p "${PROJECT_NAME}" up -d
    docker exec -it ilolly-mysql bash /opt/database_init.sh
    ;;
build)
    docker-compose -p "${PROJECT_NAME}" build
    ;;
develop)
    make_storage_folder
    docker-compose  -p "${PROJECT_NAME}" down
    docker-compose  -p "${PROJECT_NAME}" -f docker-compose-develop.yml up
    ;;
package)
    docker-compose  -p "${PROJECT_NAME}" down
    docker-compose  -p "${PROJECT_NAME}" -f docker-compose-web-package.yml up
    docker-compose  -p "${PROJECT_NAME}" -f docker-compose-web-package.yml down
    ;;
start)
    make_storage_folder
    docker-compose  -p "${PROJECT_NAME}" up -d
    ;;
stop)
    docker-compose  -p "${PROJECT_NAME}" down
    ;;
restart)
    make_storage_folder
    docker-compose  -p "${PROJECT_NAME}" restart || docker-compose  -p "${PROJECT_NAME}" up -d
    ;;
*)
    log_action_msg "Usage: command {init|build|start|stop|restart}"
    exit 2
    ;;
esac
exit 0
