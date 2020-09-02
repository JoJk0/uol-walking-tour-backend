#include <stdio.h>
#include <string.h>
#include <math.h>
#include <stdlib.h>
#include <mpi.h>

long size = 2000000;	//size of arrays constant
int noRanks, rank;
double startTime, midTime, finishTime; //variables for storing wallclock time

void StartMPI();

int main(void) {
	int i = 0;
	//MPI initialization 
	MPI_Init(NULL, NULL);
	MPI_Comm_size(MPI_COMM_WORLD, &noRanks);
	MPI_Comm_rank(MPI_COMM_WORLD, &rank);

	//checking there are more than 1 process
	if (noRanks > 1) {
		StartMPI();               
	}else{
		printf("\nYou need at least 2 processes to run parallel implementation!\n\n");
	}
		 
	MPI_Finalize();
	return 0;
}


//Start of MPI calculation
void StartMPI() {
    	   
   	if (rank == 0) {
        	printf("\nNumber of Processes Used: %d ", noRanks); 
		//start the first clock    
		startTime = MPI_Wtime();
		
	}
    
   	//Declaring global arrays			
	double tempSumA = 0, tempSumB = 0;
	double meanA = 0, meanB = 0;
	double totalA = 0, totalB = 0;
	double lastB = 0;
	//Dynamically allocating memory to both arrays
	double* a = malloc((size) * sizeof(double));
    	double* b = malloc((size) * sizeof(double));
	int i = 0, j = 0; 	
	
	//local variables
	int sizeLocal = 0;
	double offset = 0;
	
	//standard deviation and pearson correlation coefficient variables
    double tempA = 0, tempB = 0;
    double tempSum = 0, totalTempSum = 0;
    double totalA2 = 0, totalB2 = 0;
	double devB = 0, devA = 0;

	
	//Calculating the size of each processes local array	
	int leftover = size % noRanks;
	if (leftover == 0) {
		sizeLocal = size/noRanks;
	}else{
		sizeLocal = size/noRanks;
		if (rank < leftover) sizeLocal++;		
	}
    
    // Initializing local process arrays using sizeLocal
    double* localA = malloc(sizeLocal * sizeof(double));
    double* localB = malloc(sizeLocal * sizeof(double));
    
    
     // calculates an offset from 0 for each process so different things are calculated         
    if (rank < leftover) {
        offset = rank * sizeLocal;
    }else{
        offset = (rank * sizeLocal) + leftover;
    }
    
   // ~~~~~~~~~~~~~~~~~~~~LOCAL ARRAY INITIALISATION~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    for (i = 0; i < sizeLocal; i++) {    
        if (i == 0) localA[i] = sin(i + offset);
               
        localB[i] = sin(i + 5 + offset);
        
        if (i < sizeLocal-1) localA[i+5] = localB[i];       
		
	tempSumA += localA[i]; //sum of localA
    }

    // the last B must be sent back to process 0 for calculation of totalB    
    if (rank == noRanks-1) {
        lastB = localB[sizeLocal-1];
        MPI_Send(&lastB, 1, MPI_DOUBLE, 0, 0, MPI_COMM_WORLD);
    }
    
    // Reducing local a to totalA
    MPI_Reduce(&tempSumA, &totalA, 1, MPI_DOUBLE, MPI_SUM, 0, MPI_COMM_WORLD);
        
    // Recording the time for calculation 
	midTime = MPI_Wtime();
    
    // ~~~~~~~~~~~~~~~~~~~~CALCULATING MEAN~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    if (rank == 0) {
        //process 0 receives noRanks-1
        MPI_Recv(&lastB, 1, MPI_DOUBLE, noRanks-1, MPI_ANY_TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
                
        meanA = totalA / size;
        
        totalB = totalA + lastB;
        meanB = totalB / size;

    }
        
     // ~~~~~~~~~~~~~~~~~~~~Broadcasting MEAN~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    MPI_Bcast(&meanA, 1, MPI_DOUBLE, 0, MPI_COMM_WORLD);
    MPI_Bcast(&meanB, 1, MPI_DOUBLE, 0, MPI_COMM_WORLD);
      

	//calculating the numerator for both the standard deviation and pearson correlation coefficient
    for (j = 0; j < sizeLocal; j++) {		
        tempA += (localA[j] - meanA) * (localA[j] - meanA); // For Deviations values
        tempB += (localB[j] - meanB) * (localB[j] - meanB);
       
        tempSum += (localA[j] - meanA) * (localB[j] - meanB); // For Pearson value
    }
 
 
	//Reducing local values to an overall sum
    MPI_Reduce(&tempA, &totalA2, 1, MPI_DOUBLE, MPI_SUM, 0, MPI_COMM_WORLD);
    MPI_Reduce(&tempB, &totalB2, 1, MPI_DOUBLE, MPI_SUM, 1, MPI_COMM_WORLD);
    MPI_Reduce(&tempSum, &totalTempSum, 1, MPI_DOUBLE, MPI_SUM, 0, MPI_COMM_WORLD);

    //freeing memory
    free(localA); free(localB); free(a); free(b);
 
// ~~~~~~~~~~~~~~~~~~~~CALCULATING STANDARD DEVIATION~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
    if (rank == 1) {
        devB = sqrt(totalB2 / size);	//process 1 calculating Deviation B
        MPI_Send(&devB, 1, MPI_DOUBLE, 0, 0, MPI_COMM_WORLD);
    }
    if (rank == 0) {
        devA = sqrt(totalA2 / size);	//process 0 calculating Deviation A
        printf("\nStandard Deviation A: %.6f", devA);
        
        MPI_Recv(&devB, 1, MPI_DOUBLE, 1, MPI_ANY_TAG, MPI_COMM_WORLD, MPI_STATUS_IGNORE);
        printf("\nStandard Deviation B: %.6f\n", devB);
        
// ~~~~~~~~~~~~~~~~~~~~CALCULATING Pearson Correlation Coefficient~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~ 
        totalTempSum = totalTempSum / size;
		printf("\nPearson Correlation Coefficient: %.6f\n", totalTempSum / (devA * devB));

        //END Timer
		finishTime = MPI_Wtime();
		
		//Printing wallclock times	
		printf("\nWallclock Time:\n");
		printf("Declaration = %f s\n", midTime-startTime);
		printf("Calculation = %f s\n", finishTime-midTime);
		printf("Total = %f s\n\n", finishTime-startTime);
	}     
}