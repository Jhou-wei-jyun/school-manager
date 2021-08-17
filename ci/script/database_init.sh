#!/bin/bash

DIR_ROOT=$(CDPATH= cd -- "$( dirname -- "$0" )" && pwd)

function wait_service_port() {
  local MAX_SECONDS=120 && local CURRENT_SECONDS=1 && local SERVICE_LAUNCHED=0
  local PORT="$1"
  echo "Wait service port: [${PORT}]"

  while [ ${CURRENT_SECONDS} -le ${MAX_SECONDS} -a ${SERVICE_LAUNCHED} == 0 ];
  do
    CURRENT_SECONDS=`expr ${CURRENT_SECONDS} + 1`
    SEARCH_RESULT=`netstat -tulpn 2>&1 | grep ":${PORT} "`

    if [ -z "${SEARCH_RESULT}" ];
    then
      sleep 1
    else
      SERVICE_LAUNCHED=1
    fi
  done
}

wait_service_port 3306
sleep 3

mysql -u root -p"${MYSQL_ROOT_PASSWORD}" ${MYSQL_DATABASE} < ${DIR_ROOT}/bootstrap_data.sql
