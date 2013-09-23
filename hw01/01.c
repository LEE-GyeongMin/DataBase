#include <stdio.h>

#define myType long

myType myFunction(int n);

int main(void) {

	printf("01 result : %ld\n", myFunction(9));
	
	return 0;
}


myType myFunction(int n) {
	if (n == 1) {
		return 2;
	}

	else if (n == 2) {
		return 3;
	}

	return myFunction(n - 1) * myFunction(n - 2);
}
