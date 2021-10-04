import boto3

# Let's use Amazon S3
s3 = boto3.resource('s3')
obj = s3.Object(bucket_name='megabucketboy', key='index.php')
response = obj.get()
data = response['Body'].read()
print(data)