import sys
import syllables
print(syllables.estimate(sys.argv[1].lower()), end = '')
print(syllables.estimate(sys.argv[2].lower()), end = '')
print(syllables.estimate(sys.argv[3].lower()), end = '')      
