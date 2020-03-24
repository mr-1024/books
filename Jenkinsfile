pipeline {
    agent any
    stages {
        // 拉取代码
        stage('代码拉取') {
            steps {
                echo "工作目录：${WORKSPACE}"
                echo "当前目录："
                sh 'pwd'
                git branch: "${Branch}", credentialsId: "${GitCredentialsId}", url: "${GitUrl}"
                echo "当前目录文件："
                sh "ls -alh"
            }
        }
        stage('打包代码') {
            steps {
                echo "工作目录：${WORKSPACE}"
                echo "当前目录："
                sh 'pwd'
                echo '查看目录文件'
                sh 'ls -alh'
                echo '删除tar.gz文件'
                sh 'rm -rf *.tar.gz'
                sh "tar zcvf ${WORKSPACE}/books-${BUILD_ID}.tar.gz ./*  --exclude=./git && cd ../"
                echo "当前目录文件："
                sh "ls -alh"
            }
        }
        stage('上传代码') {
            steps{
                echo '上传代码'
                echo "工作目录：${WORKSPACE}"
                echo "当前目录："
                sh 'pwd'
                echo '准备上传：'
                sh 'ssh -v ${RemoteLogin} -p ${RemoteLoginPort} "cd ${RemoteTmpDir} &&  mkdir -p ${BUILD_ID} &&  chmod 777 ${BUILD_ID}"'
                sh 'scp -P ${RemoteLoginPort} ${WORKSPACE}/books-${BUILD_ID}.tar.gz ${RemoteLogin}:${RemoteTmpDir}/${BUILD_ID}'
                echo '上传临时目录完成,开始解压'
                sh 'ssh ${RemoteLogin} -p ${RemoteLoginPort} "cd ${RemoteTmpDir}/${BUILD_ID} &&  tar xf books-${BUILD_ID}.tar.gz -C ${RemoteDir}"'
                echo '解压完成'
            }
        }
        stage('删除本地压缩包') {
            steps{
                echo '上传代码'
                echo "工作目录：${WORKSPACE}"
                echo "当前目录："
                sh 'pwd'
                echo '准备删除：'
                sh 'rm -rf ${WORKSPACE}/books-${BUILD_ID}.tar.gz'
                echo '删除完成'
                sh 'ls -alh'
            }
        }
    }
}
