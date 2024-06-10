pipeline {
    agent any

    parameters {
        string(name: 'VERSION', defaultValue: '', description: 'Version to Deploy')
    }

    environment {
        ORANGEHRM_REPO = 'https://github.com/yamunasreeoggu/orangehrm.git'
        EC2_INSTANCE = '172.31.53.214'
    }

    stages {
        stage('Checkout') {
            steps {
                git credentialsId: 'git', url: "${ORANGEHRM_REPO}", branch: "${params.VERSION}"
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
                    sh "scp -o StrictHostKeyChecking=no orangehrm-${params.VERSION}.zip ec2-user@${EC2_INSTANCE}:/tmp/"
                    sh "ssh -o StrictHostKeyChecking=no ec2-user@${EC2_INSTANCE} unzip -o /tmp/orangehrm-${params.VERSION} -d /var/www/html/orangehrm-${params.VERSION}"
                }
            }
        }
    }
}
