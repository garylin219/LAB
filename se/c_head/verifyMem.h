#include <stdio.h>
#include <stdlib.h>

#define FXB 0x601000
#define HXB 0x800000
#define DYB 0x7fff00001000

void *__sbrk__;
int __bss__;    // uninitialized global variable
int __data__=1; // initialized global variable

void *__heap__;
void *__stack__;

void *__address__(void *p)
{
    if((unsigned long int)p<(unsigned long int)__heap__)
        return (void *)((unsigned long)p-((unsigned long)&__data__)+FXB);
    else if((unsigned long)p<=(unsigned long)sbrk(0))
        return (void *)((unsigned long)p-(unsigned long)__heap__+HXB);
    else
        return (void *)(DYB-((unsigned long)__stack__ - (unsigned long)p));
}
