@Grapes(
    @Grab(group='org.codehaus.groovy.modules.http-builder', module='http-builder', version='0.7.1')
)
import static groovyx.net.http.ContentType.TEXT
// Define URL variable
String taskURL = "http://http://18.221.212.155:9000/api/ce/task?id=TASK_ID"
String projectStatusURL = "http://http://18.221.212.155:9000/api/qualitygates/project_status?analysisId="

// Get project status
def status=taskURL(taskURL).task.status
while ( status == "PENDING" || status == "IN_PROGRESS" ) {
println "waiting for sonar results"
status = httpClient(taskURL).task.status
sleep(1000)
}
      assert status != "CANCELED" : "Build fail because sonar project is CANCELED"
      assert status != "FAILED" : "Build fail because sonar project is FAILED"
      def qualitygates= httpClient(projectStatusURL + httpClient(taskURL).task.analysisId)
      assert qualitygates.projectStatus.status != "ERROR" : "Build fail because sonar project status is not ok"
      println "Huraaaah! You made it :) Sonar Results are good"

def httpClient(String url){
    def taskClient = new groovyx.net.http.HTTPBuilder(url)
    taskClient.setHeaders(Accept: 'application/json')
    def response =  taskClient.get(contentType: TEXT)
    def sluper = def new groovy.json.JsonSlurper().parse(response)
}      
