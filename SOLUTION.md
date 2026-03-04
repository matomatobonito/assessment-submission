# Solution - Matt Ward

## Task Completed
Backend/Full-Stack

## Time Spent
3 hours

## Approach
My initial approach was to spend some time leading up to the assessment learning as much as I could about Symfony and Doctrine as these are not frameworks I have ever encountered before, with a particular focus on documentation and resources pertaining to Controllers and Entities as they were a key part of the intended solution.

Upon starting the assessment I finished the ER diagram, and then looked into the code and determined where and how I thought I would need to complete the solution, and also considered testing strategies for validation and error handling.

## Implementation Details
TASK 1: 

ER diagram complete, of particular relevance to the assignment was that an instance could have many answers, and that each individual answer option could be assinge to multiple answers as well. There is also a many-to-many relationship between assessments and questions, as indicated by the join table.

TASK 2: 

The getProgress and score method's scoring algorithm uses three variables, the sum of points the user scored for an element ($elementTotalScore), the maximum number of points available for that element ($maxTotalScore), and the number of questions answered in that element to date ($elementAnsweredQuestions). Each is initially set at zero.

The highest option value is found for each question, and this is added to the maximum score for each element (5 for the Likert scale being used), as well as the overall maximum score.

The user's score is then retrieved and added into a running total for that element and overall and the total number of questions answered in the element is incremented.

A percentage is then recorded of how many questions have been answered in an element vs the total number of questions for that element.

The score is then normalised for each element. This is done by subtracting the number of answered questions from the total score and is done to make the percentage representation of the score more useful (e.g. mathematically 1 out of 5 would be 20% but for a statistic analysis it's better to have it represent 0%). The same is done for the max score and a rounded overall percentage is calculated.

Once this has been done for every element the total number of answers is counted, the total score is normalised as above and the maximum overall percentage for the entire suite of answers is calculated.

Error handling to be included for this algorithm would include checks to make sure that an instance existed, that a session had answers associated with it and to validate the scoring (e.g. excluding all numerical values below 1 and above 5, including a null value).

TASK 3:

The intended solution took in a JSON object containing all the data and split it out into its various elements. Checks were then performed to see if these elements existed, with a Bad Request HTTP error being thrown if they didn't.

An EntityManager object was use to communicate with the database, first to check whether the provided assessment instances and answer options existed. If they didn't, a File Not Found error was thrown.

A further check was used to see if the provided answer option correctly matched the question to which it was assigned, again a Bad Request error was thrown in case of failure.

Finally, the stored elements were added to a new AssessmentAnswer object, and the EntityManager object would persist this new answer to the database using the flush() command. Finally a HTTP Created response would be returned to the user, as long with a JSON representation of the newly created object's ID.

The solution was abstracted into its own Answer folder as it deals directly with the AssessmentAnswer object, as well as having its own file so that the controller can be considered a single use object.

## Tools & Libraries Used
No tools or libraries were used other than the ones already provided.

## Testing
The intended testing strategy was to use the provided CURL command to test the happy path solution and then modify it in various ways to check all the provided error messages returned as expected:

Null instanceId would return 400
Null questionId would return 400
Null optionId would return 400
Malformed instanceId wpuld return 404
Malformed optionId would return 404
Malformed questionId would return 400 as returned questionId from option would not match

## Challenges & Solutions

Unfortunately the solution remains incomplete as further development was blocked by an error when submitting the provided CURL request. This error said that the AssessmentAnswer object was excluded from autowiring in the YAML config file, and despite looking for an answer I was unable to find a solution to this, which gives the possibility that I'd gone the wrong way with my proposed request in the first place!

Prior to this, I had a little bit of difficulty figuring out which entities I actually needed to include in the solution, but consulting the domain and the ER diagram again, I was able to ascertain that Instance, Option and Question (shorthand) were all needed.

I was also confused as to why questionId was included in the request, as it didn't appear to be used in the construction of anything to do with the Answer object. Again, reinvestigation gave me the idea that it could be used to be compared with the questionId in the database to ensure the option was associated with the correct question. 

## Trade-offs & Future Improvements
PHPUnit testing
Implementing PUT and DELETE requests (currently the database seems to persist all answers for all questions using the createdAt attribute to retrieve the latest one, which would soon overload it)
Testing for further edge cases (e.g. if a text answer for q4 was provided that was long enough to time the request out, that would potentially need validation)
Front end implementation (using radio buttons or a slider for Q1-3 to limit the chance of human error)