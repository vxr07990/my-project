node {
    def server
    def buildInfo
    def rtGradle
    
    stage ('Clone') {
        git url: 'https://github.com/vxr07990/my-project'
    }
 
    stage ('Artifactory configuration') {
        // Obtain an Artifactory server instance, defined in Jenkins --> Manage:
        server = Artifactory.server 'jfrog'

        rtGradle = Artifactory.newGradleBuild()
        rtGradle.tool = 'gradle' // Tool name from Jenkins configuration
        rtGradle.deployer repo: 'move-local', server: server
        rtGradle.resolver repo: 'gradle-virtual', server: server
        rtGradle.deployer.deployArtifacts = false // Disable artifacts deployment during Gradle run
        
        buildInfo = Artifactory.newBuildInfo()
    }
 
    stage ('Deploy') {
        rtGradle.run rootDir: '/var/lib/jenkins/workspace/$JOB_NAME', buildFile: 'build.gradle', tasks: ' artifactoryPublish', buildInfo: buildInfo
        rtGradle.deployer.deployArtifacts buildInfo
    }
    
    stage ('Publish build info') {
        server.publishBuildInfo buildInfo
    }
}
