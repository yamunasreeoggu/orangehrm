pipeline {
    agent any

    parameters {
        string(name: 'VERSION', defaultValue: '', description: 'Version to Deploy')
    }

    environment {
        ORANGEHRM_REPO = 'https://github.com/orangehrm/orangehrm.git'
        EC2_INSTANCE = '172.31.53.214'
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: "${params.VERSION}", url: "${ORANGEHRM_REPO}"
            }
        }

        stage('Build') {
            steps {
                dir('devTools/core') {
                  sh "composer install"
                }
            }
        }

        stage('Making Zip file') {
            steps {
                sh "zip -r orangehrm-${params.VERSION}.zip ./"
            }
        }

        stage('Deploy') {
            steps {
                sshagent(['SSH_KEY']) {
                  sh "scp  -o StrictHostKeyChecking=no orangehrm-${params.VERSION}.zip ec2-user@${EC2_INSTANCE}:/tmp/"
                  sh "ssh  -o StrictHostKeyChecking=no ec2-user@${EC2_INSTANCE} unzip -o /tmp/orangehrm-${params.VERSION} -d /var/www/html/orangehrm-${params.VERSION}"
                }
            }
        }
    }
}

-------------------------------------------------
// Using zip file


pipeline {
    agent any

    parameters {
        string(name: 'VERSION', defaultValue: '', description: 'Version to Deploy')
    }

    environment {
        GIT_REPO_URL = 'https://github.com/orangehrm/orangehrm.git'
        ZIP_FILE_URL = "https://github.com/orangehrm/orangehrm/releases/download/v${params.VERSION}/orangehrm-${params.VERSION}.zip"
        EC2_INSTANCE = '172.31.53.214'

    }

    stages {
        stage('Download ZIP') {
            steps {
                script {
                    sh "curl -o orangehrm-${params.VERSION}.zip ${ZIP_FILE_URL}"
                }
            }
        }

        stage('Deploy') {
            steps {
                sshagent(['SSH_KEY']) {
                    sh "scp -o StrictHostKeyChecking=no orangehrm-${params.VERSION}.zip ec2-user@${EC2_INSTANCE}:/tmp/"
                    sh "ssh -o StrictHostKeyChecking=no ec2-user@${EC2_INSTANCE} unzip -o /tmp/orangehrm${params.VERSION} -d /var/www/html/orangehrm-${params.VERSION}"
                }
            }
        }
    }
}

