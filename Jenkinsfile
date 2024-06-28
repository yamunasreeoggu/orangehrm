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
                    sh "wget ${ZIP_FILE_URL}"
                }
            }
        }

        stage('Deploy') {
            steps {
                sshagent(['SSH_KEY']) {
                    sh "scp -o StrictHostKeyChecking=no orangehrm-${params.VERSION}.zip ec2-user@${EC2_INSTANCE}:/tmp/"
                    sh "ssh -o StrictHostKeyChecking=no ec2-user@${EC2_INSTANCE} unzip -o /tmp/orangehrm-${params.VERSION} -d /var/www/html/orangehrm-${params.VERSION}"
                }
            }
        }
    }
}
-------------------------------------------------------------------------
ZULIP

pipeline {
    agent any

    environment {
        ZULIP_REPO = 'https://github.com/zulip/zulip.git'
        EC2_INSTANCE = '172.31.80.189'
    }

    stages {
        stage('Checkout') {
            steps {
                echo "Checkout"
            }
        }

        stage('Build') {
            steps {
                echo "Build"
            }
        }

        stage('Deploy') {
            steps {
                sshagent(['SSH_KEY_ZULIP']) {
                  sh "ssh  -o StrictHostKeyChecking=no"
                  sh "curl -fLO https://download.zulip.com/server/zulip-server-latest.tar.gz"
                  sh "tar -xf zulip-server-latest.tar.gz"
                  sh "sudo su"
                  sh "./zulip-server-*/scripts/setup/install --certbot --email=yamunasree321@gmail.com --hostname=zulip.yamunadevops.online"
                }
            }
        }
    }
}


