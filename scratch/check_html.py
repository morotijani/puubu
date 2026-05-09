
import sys

def check_div_nesting(filename):
    with open(filename, 'r', encoding='utf-8') as f:
        lines = f.readlines()
    
    stack = []
    for i, line in enumerate(lines):
        line_num = i + 1
        # Very crude check
        opens = line.count('<div')
        closes = line.count('</div')
        
        for _ in range(opens):
            stack.append(line_num)
        for _ in range(closes):
            if stack:
                stack.pop()
            else:
                print(f"Extra closing div at line {line_num}")
    
    if stack:
        for line in stack:
            print(f"Unclosed div starting at line {line}")

if __name__ == "__main__":
    check_div_nesting(sys.argv[1])
