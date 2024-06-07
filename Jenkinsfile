pipeline {
    agent any

    parameters {
        string(name: 'VERSION', defaultValue: '1.0', description: 'Version to Deploy')
    }

    environment {
        ORANGEHRM_REPO = 'https://github.com/yamunasreeoggu/orangehrm.git'
        EC2_INSTANCE = '54.236.46.50'
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
                withCredentials([sshUserPrivateKey(credentialsId: 'your-credentials-id', keyFileVariable: 'SSH_KEY')]) {
                    sh "scp -i ${SSH_KEY} target/orangehrm-${params.VERSION}.tar.gz ec2-user@${EC2_INSTANCE}:/var/www/html/"
                }
            }
        }
    }
}
