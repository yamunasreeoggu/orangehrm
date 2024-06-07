pipeline {
    agent any

    parameters {
        string(name: 'VERSION', defaultValue: '', description: 'Version to Deploy')
    }

    environment {
        ORANGEHRM_REPO = 'https://github.com/orangehrm/orangehrm.git'
        EC2_INSTANCE = '172.31.56.235'
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'main', url: "${ORANGEHRM_REPO}"
            }
        }

        stage('Build') {
            steps {
                sh 'composer install'
            }
        }

        stage('Deploy') {
            steps {
                withCredentials([sshUserPrivateKey(credentialsId: 'SSH_KEY', keyFileVariable: 'SSH_KEY')]) {
                    sh "scp -i ${SSH_KEY} target/orangehrm-${params.VERSION}.tar.gz ec2-user@${EC2_INSTANCE}:/var/www/html/"
                }
            }
        }
    }
}
