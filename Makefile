.PHONY: all init clean-all clean-config clean-runtime clean-tests

# Default target
all: init

# Initialize the application
init:
	./init

# Clean config, runtime folders and artifacts from tests
clean-all: clean-config clean-runtime clean-tests

# Clean configuration files created by init
clean-config:
	rm -f yii yii_test yii.bat yii_test.bat
	rm -f api/config/main-local.php
	rm -f api/config/params-local.php
	rm -f api/config/test-local.php
	rm -f api/web/index.php
	rm -f api/web/index-test.php
	rm -f backoffice/config/main-local.php
	rm -f backoffice/config/params-local.php
	rm -f backoffice/config/test-local.php
	rm -f backoffice/web/index.php
	rm -f backoffice/web/index-test.php
	rm -f common/config/main-local.php
	rm -f common/config/params-local.php
	rm -f common/config/test-local.php
	rm -f common/config/codeception-local.php
	rm -f console/config/main-local.php
	rm -f console/config/params-local.php
	rm -f console/config/test-local.php
	rm -f frontpage/config/main-local.php
	rm -f frontpage/config/params-local.php
	rm -f frontpage/config/test-local.php
	rm -f frontpage/web/index.php
	rm -f frontpage/web/index-test.php

# Clean runtime folders
clean-runtime:
	rm -rf api/runtime/*
	rm -rf api/web/assets/*
	rm -rf backoffice/runtime/*
	rm -rf backoffice/web/assets/*
	rm -rf console/runtime/*
	rm -rf frontpage/runtime/*
	rm -rf frontpage/web/assets/*

# Clean test output files and code coverage reports
clean-tests:
	rm -rf api/tests/_output/*
	rm -rf backoffice/tests/_output/*
	rm -rf common/tests/_output/*
	rm -rf console/tests/_output/*
	rm -rf frontpage/tests/_output/*
