pipeline {
    agent any

    environment {
        // Укажите данные для подключения к серверу
        SSH_USER = 'root'
        SSH_HOST = '176.113.80.94'
        SSH_KEY = credentials('your_ssh_private_key_file')
        DEPLOY_DIR = '/'
    }

    stages {
        stage('Checkout') {
            steps {
                git branch: 'production', url: 'https://github.com/RemoteTechnology/SportExperts'
            }
        }

        stage('Build') {
            steps {
                sh 'cd SportExperts'
                sh 'cp .example.env .env'
                sh 'docker-compose up --build -d'
                sh 'cp sources/.example.env sources/.env'
                sh 'docker-compose run php-fpm composer --ignore-platform-req=ext-sockets install'
                sh 'docker-compose run node install'
                sh 'docker-compose run node run build'
                sh 'docker-compose run php-fpm php artisan key:generate'
                sh 'chmod -R 777 $PWD'
                sh 'docker-compose run php-fpm php artisan storage:link'
            }
        }

        stage('Deploy') {
            steps {
                sshagent(credentials: [SSH_KEY]) {
                    sh """
                    ssh -o StrictHostKeyChecking=no ${SSH_USER}@${SSH_HOST} 'mkdir -p ${DEPLOY_DIR}'
                    scp -r ./build/* ${SSH_USER}@${SSH_HOST}:${DEPLOY_DIR}
                    """
                }
            }
        }
    }

    post {
        success {
            echo 'Deployment successful!'
        }
        failure {
            echo 'Deployment failed!'
        }
    }
}